<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnswerModel;
use App\Models\KuesionerModel; // pastikan model ini sesuai dengan yang kamu pakai untuk atasan
use App\Models\DetailaccountAtasan;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Provincies;
use App\Models\Cities;
use App\Models\AccountModel;
use App\Models\LogActivityModel;
use App\Models\ResponseModel;

class AtasanKuesionerController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;
    protected $detailAccountModel;
    protected $accountModel;
    protected $logActivityModel;

    public function __construct()
    {
        $this->questionnaireModel = new KuesionerModel();
        $this->answerModel        = new AnswerModel();
        $this->detailAccountModel = new DetailaccountAtasan();
        $this->accountModel       = new AccountModel();
        $this->logActivityModel   = new LogActivityModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id_account');
        $userData = session()->get();

        // Filter kuesioner: hanya tampilkan jika role_id == 8 ada di conditional logic
        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);
        $filteredQuestionnaires = [];

        foreach ($questionnaires as $q) {
            $conditional = $q['conditional_logic'] ?? '';
            if (empty($conditional) || $conditional === '[]') {
                // Skip jika tidak ada conditional logic
                continue;
            }

            // Cek apakah role_id == 8 ada di conditional logic
            $conditions = is_string($conditional) ? json_decode($conditional, true) : $conditional;
            $hasRole8 = false;
            foreach ($conditions as $condition) {
                if ($condition['field'] === 'role_id' && $condition['operator'] === 'is' && $condition['value'] === '8') {
                    $hasRole8 = true;
                    break;
                }
            }

            // Hanya masukkan questionnaire jika role_id == 8 ada dan semua kondisi match
            if ($hasRole8 && $this->questionnaireModel->checkConditions($conditional, $userData)) {
                $filteredQuestionnaires[] = $q;
            }
        }

        $data = [];
        foreach ($filteredQuestionnaires as $q) {
            if ($q['is_active'] !== 'active') continue;

            $internalStatus = $this->answerModel->getStatus($q['id'], $userId) ?: 'draft';
            $statusPengisian = $this->mapStatusForView($internalStatus, $q['id'], $userId);
            $progress = $this->calculateProgressForView($statusPengisian, $q['id'], $userId, $userData);

            $data[] = [
                'id'          => $q['id'],
                'judul'       => $q['title'],
                'statusIsi'   => $statusPengisian,
                'progress'    => $progress,
                'is_active'   => $q['is_active'],
            ];
        }

        return view('atasan/kuesioner/index', ['data' => $data]);
    }

    public function mulai($q_id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id_account');
        $userData = session()->get();

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire || $questionnaire['is_active'] !== 'active') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak ditemukan atau tidak aktif');
        }

        // Validasi akses: pastikan role_id == 8 ada di conditional
        $conditional = $questionnaire['conditional_logic'] ?? '';
        if (empty($conditional) || $conditional === '[]') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk atasan');
        }

        $conditions = is_string($conditional) ? json_decode($conditional, true) : $conditional;
        $hasRole8 = false;
        foreach ($conditions as $condition) {
            if ($condition['field'] === 'role_id' && $condition['operator'] === 'is' && $condition['value'] === '8') {
                $hasRole8 = true;
                break;
            }
        }

        if (!$hasRole8 || !$this->questionnaireModel->checkConditions($conditional, $userData)) {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk Anda');
        }

        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $userData);
        $previousAnswers = $this->answerModel->getAnswers($q_id, $userId);
        $status = $this->answerModel->getStatus($q_id, $userId);
        $progress = $this->answerModel->calculateProgress($q_id, $userId, $structure); // Hitung progress

        if ($status === 'completed' && !empty($questionnaire['announcement'])) {
            $announcement = $this->sanitizeAnnouncementContent($questionnaire['announcement']);
            return view('atasan/kuesioner/announcement', ['announcement' => $announcement]);
        } elseif ($status === 'completed') {
            return redirect()->to('atasan/kuesioner/lihat/' . $q_id);
        }

        return view('atasan/kuesioner/fill', [
            'q_id' => $q_id,
            'structure' => $structure,
            'previous_answers' => $previousAnswers,
            'progress' => $progress, // Kirim progress ke view
            'announcement' => $questionnaire['announcement'] ? $this->sanitizeAnnouncementContent($questionnaire['announcement']) : null
        ]);
    }

    public function lanjutkan($q_id)
    {
        return $this->mulai($q_id); // Re-use mulai() logic, progress sudah dihitung di sana
    }

    public function save($q_id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id_account');
        $userData = session()->get();

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire || $questionnaire['is_active'] !== 'active') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak ditemukan atau tidak aktif');
        }

        // Validasi akses sama seperti mulai()
        $conditional = $questionnaire['conditional_logic'] ?? '';
        if (empty($conditional) || $conditional === '[]') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk atasan');
        }

        $conditions = is_string($conditional) ? json_decode($conditional, true) : $conditional;
        $hasRole8 = false;
        foreach ($conditions as $condition) {
            if ($condition['field'] === 'role_id' && $condition['operator'] === 'is' && $condition['value'] === '8') {
                $hasRole8 = true;
                break;
            }
        }

        if (!$hasRole8 || !$this->questionnaireModel->checkConditions($conditional, $userData)) {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk Anda');
        }

        // Handle file uploads
        $files = $this->request->getFiles();
        $answers = $this->request->getPost('answer') ?? []; // Adjust to match form input name
        foreach ($files as $field => $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/answers/', $newName);
                $questionId = str_replace('files_', '', $field);
                $answers[$questionId] = $newName;
            }
        }

        // Simpan jawaban
        $this->answerModel->saveAnswers($q_id, $userId, $answers);

        // Cek completion
        if ($this->answerModel->isCompleted($q_id, $userId)) {
            $this->answerModel->setQuestionnaireCompleted($q_id, $userId, true);
            if (!empty($questionnaire['announcement'])) {
                return redirect()->to('atasan/kuesioner/lihat/' . $q_id)->with('announcement', $questionnaire['announcement']);
            }
            return redirect()->to('atasan/kuesioner/lihat/' . $q_id)->with('success', 'Kuesioner selesai');
        }

        return redirect()->to('atasan/kuesioner/lanjutkan/' . $q_id)->with('success', 'Jawaban disimpan');
    }

    public function lihat($q_id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id_account');
        $userData = session()->get();

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire || $questionnaire['is_active'] !== 'active') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak ditemukan atau tidak aktif');
        }

        $conditional = $questionnaire['conditional_logic'] ?? '';
        if (empty($conditional) || $conditional === '[]') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk atasan');
        }

        $conditions = is_string($conditional) ? json_decode($conditional, true) : $conditional;
        $hasRole8 = false;
        foreach ($conditions as $condition) {
            if ($condition['field'] === 'role_id' && $condition['operator'] === 'is' && $condition['value'] === '8') {
                $hasRole8 = true;
                break;
            }
        }

        if (!$hasRole8 || !$this->questionnaireModel->checkConditions($conditional, $userData)) {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner tidak diperuntukkan untuk Anda');
        }

        $status = $this->answerModel->getStatus($q_id, $userId);
        if ($status !== 'completed') {
            return redirect()->to('atasan/kuesioner')->with('error', 'Kuesioner belum selesai');
        }

        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $userData);
        $answers = $this->answerModel->getAnswers($q_id, $userId);
        $progress = $this->answerModel->calculateProgress($q_id, $userId, $structure); // Hitung progress untuk review

        $announcement = session()->getFlashdata('announcement') ? $this->sanitizeAnnouncementContent(session()->getFlashdata('announcement')) : null;

        return view('atasan/kuesioner/review', [
            'q_id' => $q_id,
            'structure' => $structure,
            'answers' => $answers,
            'progress' => $progress, // Kirim progress ke review jika diperlukan
            'announcement' => $announcement
        ]);
    }

    private function mapStatusForView($internalStatus, $questionnaireId, $userId)
    {
        if ($internalStatus === 'completed') {
            return 'Finish';
        }
        $progress = $this->answerModel->calculateProgress($questionnaireId, $userId);
        return $progress > 0 ? 'On Going' : 'Belum Mengisi';
    }

    private function calculateProgressForView($viewStatus, $questionnaireId, $userId, $userData)
    {
        if ($viewStatus === 'Finish') {
            return 100;
        }
        $structure = $this->questionnaireModel->getQuestionnaireStructure($questionnaireId, $userData);
        return $this->answerModel->calculateProgress($questionnaireId, $userId, $structure);
    }

    private function sanitizeAnnouncementContent($content)
    {
        return esc($content, 'html');
    }
}
