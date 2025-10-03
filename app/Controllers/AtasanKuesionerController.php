<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnswerModel;
use App\Models\KuesionerModel;
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

    /**
     * Daftar semua kuesioner atasan
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id_account');
        $userData = session()->get();

        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);

        $data = [];
        foreach ($questionnaires as $q) {
            if ($q['is_active'] === 'inactive') continue;

            $statusPengisian = $this->answerModel->getStatus($q['id'], $userId) ?: 'Belum Mengisi';
            $progress        = ($statusPengisian === 'On Going')
                ? $this->answerModel->getProgress($q['id'], $userId)
                : 0;

            $data[] = [
                'id'          => $q['id'],
                'judul'       => $q['title'],
                'statusIsi'   => $statusPengisian,
                'progress'    => $progress,
                'is_active'   => $q['is_active'],
                'conditional' => $q['conditional_logic'] ?? '-',
            ];
        }

        return view('atasan/kuesioner/index', ['data' => $data]);
    }

    /**
     * Mulai isi kuesioner atasan
     */
    public function mulai($q_id)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId   = session()->get('id_account');
        $userData = session()->get();
        $q_id     = (int)$q_id;

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire) {
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan.');
        }

        // cek syarat akses
        if (!$this->questionnaireModel->checkConditions($questionnaire['conditional_logic'] ?? '', $userData)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kuesioner ini.');
        }

        // cek status
        $status = $this->answerModel->getStatus($q_id, $userId);
        if ($status === 'Finish') {
            return redirect()->to("/atasan/kuesioner/lihat/$q_id");
        }

        // ambil jawaban sebelumnya
        $previous_answers = $this->answerModel->getUserAnswers($q_id, $userId);

        // ambil struktur pertanyaan
        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $userData, $previous_answers);
        if (empty($structure['pages'])) {
            return view('atasan/kuesioner/error', ['message' => 'Tidak ada pertanyaan yang tersedia untuk Anda.']);
        }

        $progress = $this->answerModel->getProgress($q_id, $userId);

        session()->set("current_q_id", $q_id);

        return view('atasan/kuesioner/fill', [
            'structure'        => $structure,
            'user_id'          => $userId,
            'q_id'             => $q_id,
            'progress'         => $progress,
            'previous_answers' => $previous_answers
        ]);
    }

    /**
     * Lanjutkan isi kuesioner
     */
    public function lanjutkan($q_id)
    {
        return $this->mulai($q_id);
    }

    /**
     * Review / lihat hasil kuesioner
     */
   public function lihat($q_id)
{
    $user_data = session()->get();

    // pastikan format id sama dengan alumni
    $userId = $user_data['id_account'] ?? $user_data['id'];

    $data['structure'] = $this->questionnaireModel->getQuestionnaireStructure(
        $q_id,
        $user_data,
        $this->answerModel->getUserAnswers($q_id, $userId)
    );

    if (!$data['structure']) {
        return redirect()->to('/atasan/kuesioner')
            ->with('error', 'Kuesioner tidak ditemukan atau tidak dapat diakses.');
    }

    $data['q_id']             = $q_id;
    $data['progress']         = $this->answerModel->getProgress($q_id, $userId);
    $data['previous_answers'] = $this->answerModel->getUserAnswers($q_id, $userId);

    return view('atasan/kuesioner/review', $data);
}



    /**
     * Simpan jawaban kuesioner
     */
   public function save($q_id)
{
    $user_id = session()->get('id_account');
    $answers = $this->request->getPost('answer');
    $files   = $this->request->getFiles();

    if (empty($answers) && empty($files)) {
        return redirect()->to("/atasan/kuesioner/mulai/$q_id")
            ->with('error', 'Tidak ada jawaban yang disimpan.');
    }

    // proses jawaban
    if ($answers) {
        foreach ($answers as $question_id => $answer) {
            if (empty($answer) && !is_array($answer)) {
                continue;
            }

            if (!is_array($answer) && strpos($answer, 'uploaded_file:') === 0) {
                $file = $files['answer_' . $question_id] ?? null;
                if ($file && $file->isValid()) {
                    $upload_path = WRITEPATH . 'uploads/answers/';
                    if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);
                    $new_name = $file->getRandomName();
                    $file->move($upload_path, $new_name);
                    $file_path = 'uploaded_file:' . $upload_path . $new_name;
                    $this->answerModel->saveAnswer($user_id, $q_id, $question_id, $file_path);
                }
            } else {
                $this->answerModel->saveAnswer($user_id, $q_id, $question_id, $answer);
            }
        }
    }

    // proses file upload standalone
    foreach ($files as $key => $file) {
        if (preg_match('/answer_(\d+)/', $key, $matches)) {
            $question_id = $matches[1];
            if ($file && $file->isValid()) {
                $upload_path = WRITEPATH . 'uploads/answers/';
                if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);
                $new_name = $file->getRandomName();
                $file->move($upload_path, $new_name);
                $file_path = 'uploaded_file:' . $upload_path . $new_name;
                $this->answerModel->saveAnswer($user_id, $q_id, $question_id, $file_path);
            }
        }
    }

    $this->logActivityModel->logAction(
        'submit_questionnaire',
        'Atasan ' . $user_id . ' submitted questionnaire ID ' . $q_id
    );

    return redirect()->to("/atasan/kuesioner/mulai/$q_id")
        ->with('success', 'Jawaban berhasil disimpan.');
}


    /**
     * Landing respon (ringkasan hasil per tahun)
     */
    public function responseLanding()
    {
        $responseModel = new ResponseModel();

        $yearsRaw = $responseModel->getAvailableYears() ?? [];
        $allYears = array_column($yearsRaw, 'tahun');

        $selectedYear = $this->request->getGet('tahun');
        if (!$selectedYear && !empty($allYears)) {
            $selectedYear = $allYears[0];
        }
        if (!$selectedYear) {
            $selectedYear = date('Y');
        }

        $data = [
            'selectedYear' => $selectedYear,
            'allYears'     => $allYears,
            'data'         => $responseModel->getSummaryByYear($selectedYear)
        ];

        return view('LandingPage/respon', $data);
    }
}
