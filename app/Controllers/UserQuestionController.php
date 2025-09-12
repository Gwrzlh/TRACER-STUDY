<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnswerModel;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairConditionModel;
use App\Models\SectionModel;
use App\models\LogActivityModel;

class UserQuestionController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;
    protected $conditionModel;
    protected $logActivityModel;

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel = new AnswerModel();
        $this->conditionModel = new QuestionnairConditionModel();
        $this->logActivityModel = new LogActivityModel();
    }

    /**
     * Daftar semua kuesioner yang bisa diakses user
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id');
        $userData = session()->get();

        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);

        $data = [];
        foreach ($questionnaires as $q) {
            if ($q['is_active'] === 'inactive') {
                continue; // skip kalau tidak aktif
            }

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

        return view('alumni/questioner/index', ['data' => $data]);
    }

    /**
     * Mulai isi kuesioner
     */
    public function mulai($q_id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId   = session()->get('id');
        $userData = session()->get();
        $q_id     = (int)$q_id;

        log_message('debug', '[mulai] UserData: ' . print_r($userData, true));

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire) {
            log_message('error', '[mulai] Questionnaire not found for ID: ' . $q_id);
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan.');
        }

        // cek syarat akses
        if (!$this->questionnaireModel->checkConditions($questionnaire['conditional_logic'] ?? '', $userData)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kuesioner ini.');
        }

        // cek status
        $status = $this->answerModel->getStatus($q_id, $userId);
        if ($status === 'Finish') {
            return redirect()->to("/alumni/questioner/lihat/$q_id");
        }

        // ambil jawaban sebelumnya
        $previous_answers = $this->answerModel->getUserAnswers($q_id, $userId);
        log_message('debug', '[mulai] Previous answers: ' . print_r($previous_answers, true));

        // ambil struktur pertanyaan
        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $userData, $previous_answers);
        log_message('debug', '[mulai] Structure: ' . print_r($structure, true));

        if (empty($structure['pages'])) {
            return view('alumni/questioner/error', ['message' => 'Tidak ada pertanyaan yang tersedia untuk Anda.']);
        }

        $progress = $this->answerModel->getProgress($q_id, $userId);
        log_message('debug', '[mulai] Progress: ' . $progress);

        session()->set("current_q_id", $q_id);

        return view('alumni/questioner/fill', [
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

        $data['structure'] = $this->questionnaireModel->getQuestionnaireStructure(
            $q_id,
            $user_data,
            $this->answerModel->getUserAnswers($q_id, $user_data['id'])
        );

        if (!$data['structure']) {
            return redirect()->to('/alumni/questioner')
                ->with('error', 'Kuesioner tidak ditemukan atau tidak dapat diakses.');
        }

        $data['q_id']             = $q_id;
        $data['progress']         = $this->answerModel->getProgress($q_id, $user_data['id']);
        $data['previous_answers'] = $this->answerModel->getUserAnswers($q_id, $user_data['id']);

        return view('alumni/questioner/review', $data);
    }

    /**
     * Simpan jawaban kuesioner
     */
    public function saveAnswer()
    {
        $db      = \Config\Database::connect();
        $user_id = session()->get('id');
        $q_id    = $this->request->getPost('q_id');
        $answers = $this->request->getPost('answer');
        $files   = $this->request->getFiles();

        if (empty($answers) && empty($files)) {
            return redirect()->to("/alumni/questionnaires/mulai/$q_id")
                ->with('error', 'Tidak ada jawaban yang disimpan.');
        }

        // proses jawaban
        if ($answers) {
            foreach ($answers as $question_id => $answer) {
                if (empty($answer) && !is_array($answer)) {
                    continue; // skip kosong
                }

                // handle file upload
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
                    // non-file answer
                    $this->answerModel->saveAnswer($user_id, $q_id, $question_id, $answer);
                }
            }
        }

        // handle file upload standalone
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
        $this->logActivityModel->logAction('submit_questionnaire', 'User ' . $user_id . ' submitted questionnaire ID ' . $q_id);
        return redirect()->to("/alumni/questionnaires/mulai/$q_id")->with('success', 'Jawaban berhasil disimpan.');
    }
}
