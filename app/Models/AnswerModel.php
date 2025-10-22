<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class AnswerModel extends Model
{
    protected $table            = 'answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['questionnaire_id', 'user_id', 'question_id', 'answer_text', 'created_at', 'STATUS'];

    protected bool $allowEmptyInserts = false;

    // === METHOD EXISTING (TIDAK DIUBAH) ===
    public function getStatus($questionnaireId, $userId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('STATUS');
        $builder->where(['questionnaire_id' => $questionnaireId, 'user_id' => $userId]);
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();

        $status = $result['STATUS'] ?? 'draft';
        log_message('debug', '[AnswerModel] getStatus for Q_ID: ' . $questionnaireId . ', User ID: ' . $userId . ' returned: ' . $status);
        return $status;
    }

    public function setStatus($questionnaireId, $userId, $status)
    {
        $data = ['STATUS' => $status];
        $updated = $this->where(['questionnaire_id' => $questionnaireId, 'user_id' => $userId])->set($data)->update();
        log_message('debug', '[AnswerModel] Set STATUS for Q_ID: ' . $questionnaireId . ', User ID: ' . $userId . ' to ' . $status . ' (updated rows: ' . $updated . ')');
        return $this->getStatus($questionnaireId, $userId);
    }

    public function getProgress($questionnaire_id, $user_id)
    {
        $answered = $this->where(['questionnaire_id' => $questionnaire_id, 'user_id' => $user_id])->countAllResults();
        $totalQuestions = (new QuestionModel())->where('questionnaires_id', $questionnaire_id)->countAllResults();
        return $totalQuestions > 0 ? ($answered / $totalQuestions) * 100 : 0;
    }

    public function getUserAnswers($questionnaire_id, $user_id, bool $forAdmin = false): array
    {
        $answers = $this->select('question_id, answer_text')
            ->where([
                'questionnaire_id' => $questionnaire_id,
                'user_id'          => $user_id
            ])
            ->findAll();

        $result = [];
        foreach ($answers as $ans) {
            if ($forAdmin) {
                $result[$ans['question_id']] = $ans['answer_text'];
            } else {
                $result['q_' . $ans['question_id']] = $ans['answer_text'];
            }
        }

        log_message(
            'debug',
            '[getUserAnswers] (forAdmin=' . ($forAdmin ? 'true' : 'false') . ') ' .
                'Answers for q_id ' . $questionnaire_id . ': ' . print_r($result, true)
        );

        return $result;
    }

    public function saveAnswer($user_id, $questionnaire_id, $question_id, $answer)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        $data = [
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id,
            'answer_text'      => is_array($answer) ? json_encode($answer) : $answer,
            'STATUS'           => 'draft',
            'created_at'       => $now,
        ];

        log_message('debug', '[saveAnswer] Saving answer for question_id: ' . $question_id . ', user: ' . $user_id);

        $existing = $this->where([
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id
        ])->first();

        if ($existing) {
            $this->update($existing['id'], $data);
            log_message('info', '[saveAnswer] Updated existing answer ID: ' . $existing['id']);
        } else {
            $this->insert($data);
            log_message('info', '[saveAnswer] Inserted new answer ID: ' . $this->insertID());
        }

        $responseModel = new \App\Models\ResponseModel();
        $existingResponse = $responseModel->where('account_id', $user_id)
            ->where('questionnaire_id', $questionnaire_id)
            ->first();

        if ($existingResponse) {
            if ($existingResponse['status'] !== 'completed') {
                $responseModel->update($existingResponse['id'], [
                    'status'      => 'draft',
                    'updated_at'  => $now,
                    'ip_address'  => \Config\Services::request()->getIPAddress()
                ]);
                log_message('debug', '[saveAnswer] Updated response to draft ID: ' . $existingResponse['id']);
            }
        } else {
            $responseModel->insert([
                'account_id'       => $user_id,
                'questionnaire_id' => $questionnaire_id,
                'status'           => 'draft',
                'created_at'       => $now,
                'ip_address'       => \Config\Services::request()->getIPAddress()
            ]);
            log_message('debug', '[saveAnswer] Created new response as draft');
        }
    }

    public function setQuestionnaireCompleted($questionnaire_id, $user_id, $validateBackend = false)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();
        $responseModel = new \App\Models\ResponseModel();
        $existingResponse = $responseModel->where('account_id', $user_id)
            ->where('questionnaire_id', $questionnaire_id)
            ->first();

        if ($existingResponse && $existingResponse['status'] === 'completed') {
            log_message('info', '[setQuestionnaireCompleted] Already completed for user ' . $user_id . '. Skipping.');
            return true;
        }

        if ($validateBackend) {
            if (!$this->validateLogicalCompletion($questionnaire_id, $user_id)) {
                log_message('warning', '[setQuestionnaireCompleted] Backend validation failed.');
                return false;
            }
            log_message('debug', '[setQuestionnaireCompleted] Backend validation passed.');
        } else {
            log_message('debug', '[setQuestionnaireCompleted] Skipping backend validation.');
        }

        $this->db->transStart();

        try {
            $this->where([
                'user_id'          => $user_id,
                'questionnaire_id' => $questionnaire_id
            ])->set(['STATUS' => 'completed'])->update();
            log_message('debug', '[setQuestionnaireCompleted] Updated answers to completed. Affected rows: ' . $this->db->affectedRows());

            $lastAnswer = $this->where([
                'user_id'          => $user_id,
                'questionnaire_id' => $questionnaire_id
            ])->orderBy('created_at', 'DESC')->first();

            $submittedAt = $lastAnswer['created_at'] ?? $now;

            $responseData = [
                'submitted_at' => $submittedAt,
                'status'       => 'completed',
                'ip_address'   => \Config\Services::request()->getIPAddress(),
                'updated_at'   => $now
            ];

            if ($existingResponse) {
                $responseModel->update($existingResponse['id'], $responseData);
                log_message('info', '[setQuestionnaireCompleted] Updated response to completed ID: ' . $existingResponse['id']);
            } else {
                $responseData['account_id'] = $user_id;
                $responseData['questionnaire_id'] = $questionnaire_id;
                $responseData['created_at'] = $now;
                $responseModel->insert($responseData);
                log_message('info', '[setQuestionnaireCompleted] Inserted new completed response');
            }

            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed.');
            }
            return true;
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', '[setQuestionnaireCompleted] Error: ' . $e->getMessage());
            return false;
        }
    }

    public function validateLogicalCompletion($questionnaire_id, $user_id)
    {
        $questionnaireModel = new \App\Models\QuestionnairModel();
        $structure = $questionnaireModel->getQuestionnaireStructure($questionnaire_id, $user_id);

        if (empty($structure) || empty($structure['pages'])) {
            log_message('error', '[validateLogicalCompletion] No structure found for questionnaire ' . $questionnaire_id);
            return false;
        }

        $answers = $this->where([
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id
        ])->findAll();
        $previousAnswers = [];
        foreach ($answers as $ans) {
            $previousAnswers[$ans['question_id']] = $ans['answer_text'];
        }

        $totalRelevantQuestions = 0;
        $answeredRelevantQuestions = 0;

        foreach ($structure['pages'] as $page) {
            $isPageRelevant = $this->evaluatePageConditions($page, $previousAnswers);

            if ($isPageRelevant) {
                foreach ($page['questions'] as $question) {
                    $totalRelevantQuestions++;
                    if (isset($previousAnswers[$question['id']]) && !empty($previousAnswers[$question['id']])) {
                        $answeredRelevantQuestions++;
                    }
                }
            }
        }

        $isComplete = ($answeredRelevantQuestions >= $totalRelevantQuestions && $totalRelevantQuestions > 0);
        log_message('debug', '[validateLogicalCompletion] Answered/Relevant: ' . $answeredRelevantQuestions . '/' . $totalRelevantQuestions . ' - Complete: ' . ($isComplete ? 'YES' : 'NO'));
        return $isComplete;
    }

    private function evaluatePageConditions($page, $previousAnswers)
    {
        if (empty($page['conditions'])) {
            return true;
        }

        $conditions = $page['conditions'];
        preg_match_all('/question_(\d+) == "([^"]+)"/', $conditions, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $qId = $matches[1][$i];
            $expected = $matches[2][$i];
            if (isset($previousAnswers[$qId]) && $previousAnswers[$qId] == $expected) {
                return true;
            }
        }
        return false;
    }

    public function batchUpdateStatus($questionnaire_id, $user_id, $status)
    {
        log_message('debug', "AnswerModel::batchUpdateStatus called with questionnaire_id: {$questionnaire_id}, user_id: {$user_id}, status: {$status}");

        if (!in_array($status, ['draft', 'completed'])) {
            log_message('error', "Invalid status: {$status}");
            return false;
        }

        $builder = $this->where([
            'questionnaire_id' => $questionnaire_id,
            'user_id' => $user_id
        ]);
        $affectedCount = $builder->countAllResults(false);
        log_message('debug', "AnswerModel::batchUpdateStatus found {$affectedCount} rows to update");

        $builder->set('STATUS', $status);
        $result = $builder->update();

        $affectedRows = $this->db->affectedRows();
        log_message('debug', "AnswerModel::batchUpdateStatus result: " . ($result ? 'success' : 'failure') . ", affected rows: {$affectedRows}");

        return $result;
    }

    public function getAnswersRaw($prodiId = null, $questionId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detailaccount_alumni al');

        $builder->select("
            al.nama_lengkap as alumni_name,
            al.nim,
            al.tahun_kelulusan,
            j.nama_jurusan as jurusan_name,
            p.nama_prodi as prodi_name,
            a.answer_text,
            a.STATUS,
            q.id as question_id,
            q.question_text
        ");

        $builder->join('jurusan j', 'j.id = al.id_jurusan', 'left');
        $builder->join('prodi p', 'p.id = al.id_prodi', 'left');
        $builder->join('answers a', 'a.user_id = al.id_account AND a.STATUS="completed"', 'left');
        $builder->join('questions q', 'q.id = a.question_id', 'left');

        if (!empty($prodiId)) {
            $builder->where('p.id', $prodiId);
        }

        if (!empty($questionId)) {
            $builder->where('q.id', $questionId);
        }

        $builder->orderBy('al.nama_lengkap', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function deleteAnswerAndCheckResponse($answerId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $answer = $this->find($answerId);

        if (!$answer) {
            $db->transComplete();
            return false;
        }

        $questionnaireId = $answer['questionnaire_id'];
        $accountId       = $answer['user_id'];

        $this->delete($answerId);

        $remaining = $this->where([
            'questionnaire_id' => $questionnaireId,
            'user_id'          => $accountId
        ])->countAllResults();

        if ($remaining === 0) {
            $responseModel = new \App\Models\ResponseModel();
            $responseModel->where([
                'questionnaire_id' => $questionnaireId,
                'account_id'       => $accountId
            ])->delete();
        }

        $db->transComplete();

        return $db->transStatus();
    }

    // === METHOD BARU UNTUK ATASAN KUESIONER (TANPA MENGGANGGU ALUR LAMA) ===

    /**
     * Mendapatkan jawaban dalam format sederhana untuk fill.php (seperti getUserAnswers tapi tanpa prefix 'q_')
     * @param int $questionnaireId
     * @param int $userId
     * @return array [question_id => answer_text]
     */
    public function getAnswers($questionnaireId, $userId)
    {
        return $this->getUserAnswers($questionnaireId, $userId, true); // forAdmin = true → tanpa 'q_'
    }

    /**
     * Menyimpan semua jawaban sekaligus (untuk save() di controller)
     * @param int $questionnaireId
     * @param int $userId
     * @param array $answers [question_id => value]
     */
    public function saveAnswers($questionnaireId, $userId, $answers)
    {
        foreach ($answers as $questionId => $value) {
            $this->saveAnswer($userId, $questionnaireId, $questionId, $value);
        }
    }

    /**
     * Mengecek apakah kuesioner sudah selesai (gunakan validateLogicalCompletion)
     * @param int $questionnaireId
     * @param int $userId
     * @return bool
     */
    public function isCompleted($questionnaireId, $userId)
    {
        return $this->validateLogicalCompletion($questionnaireId, $userId);
    }

    /**
     * Hitung progress dengan struktur dinamis (gunakan getQuestionnaireStructure)
     * @param int $questionnaireId
     * @param int $userId
     * @param array|null $structure (opsional, jika sudah ada)
     * @return int
     */
    public function calculateProgress($questionnaireId, $userId, $structure = null)
    {
        if (!$structure) {
            $questionnaireModel = new \App\Models\QuestionnairModel();
            $structure = $questionnaireModel->getQuestionnaireStructure($questionnaireId, $userId);
        }

        if (empty($structure) || empty($structure['pages'])) {
            log_message('error', '[calculateProgress] No structure or pages found for questionnaire ' . $questionnaireId);
            return 0;
        }

        $totalRelevant = 0;
        $answeredRelevant = 0;
        $userAnswers = $this->getAnswers($questionnaireId, $userId);

        foreach ($structure['pages'] as $page) {
            $pageVisible = $this->evaluatePageConditions($page, $userAnswers);
            if (!$pageVisible) continue;

            // Periksa apakah ada 'sections' di dalam page
            $sections = $page['sections'] ?? [];
            if (empty($sections)) {
                log_message('debug', '[calculateProgress] No sections found in page ' . ($page['id'] ?? 'unknown'));
                continue;
            }

            foreach ($sections as $section) {
                // Periksa apakah ada 'questions' di dalam section
                $questions = $section['questions'] ?? [];
                if (empty($questions)) {
                    log_message('debug', '[calculateProgress] No questions found in section ' . ($section['id'] ?? 'unknown'));
                    continue;
                }

                foreach ($questions as $question) {
                    $totalRelevant++;
                    if (isset($userAnswers[$question['id']]) && !empty($userAnswers[$question['id']])) {
                        $answeredRelevant++;
                    }
                }
            }
        }

        $progress = $totalRelevant > 0 ? round(($answeredRelevant / $totalRelevant) * 100) : 0;
        log_message('debug', '[calculateProgress] Q_ID: ' . $questionnaireId . ', User: ' . $userId . ', Progress: ' . $progress . '% (Answered/Relevant: ' . $answeredRelevant . '/' . $totalRelevant . ')');
        return $progress;
    }

    // === END OF NEW METHODS ===

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}