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

    // Di App/Models/AnswerModel.php
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
            'STATUS'           => 'draft',  // Initially draft
            'created_at'       => $now,
        ];

        log_message('debug', '[saveAnswer] Saving answer for question_id: ' . $question_id . ', user: ' . $user_id);

        // Insert or update
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

        // Update or create response as draft if not completed
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

        // Transaction
        $this->db->transStart();

        try {
            // Update answers to completed
            $this->where([
                'user_id'          => $user_id,
                'questionnaire_id' => $questionnaire_id
            ])->set(['STATUS' => 'completed'])->update();
            log_message('debug', '[setQuestionnaireCompleted] Updated answers to completed. Affected rows: ' . $this->db->affectedRows());

            // Get submitted_at from latest answer
            $lastAnswer = $this->where([
                'user_id'          => $user_id,
                'questionnaire_id' => $questionnaire_id
            ])->orderBy('created_at', 'DESC')->first();

            $submittedAt = $lastAnswer['created_at'] ?? $now;

            // Update response
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
            // Asumsi: Implement getQuestionnaireStructure() di QuestionnaireModel
            // Contoh return: ['pages' => [['id' => 1, 'questions' => [['id' => 1, ...]], 'conditions' => 'question_1 == "yes"'], ...]]
            $questionnaireModel = new \App\Models\QuestionnairModel();
            $structure = $questionnaireModel->getQuestionnaireStructure($questionnaire_id, $user_id);

        if (empty($structure) || empty($structure['pages'])) {
            log_message('error', '[validateLogicalCompletion] No structure found for questionnaire ' . $questionnaire_id);
            return false;
        }

        // Fetch user answers
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

        // Contoh simple parser untuk conditions (adjust sesuai format conditions Anda, misal string 'question_1 == "yes" OR question_2 != ""')
        // Ini placeholder: Gunakan eval() dengan hati-hati (security risk), atau pakai library expression parser.
        // Untuk tes, asumsikan true jika conditions ada answer terkait.
        $conditions = $page['conditions'];  // Misal: 'question_1 == "yes"'
        preg_match_all('/question_(\d+) == "([^"]+)"/', $conditions, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $qId = $matches[1][$i];
            $expected = $matches[2][$i];
            if (isset($previousAnswers[$qId]) && $previousAnswers[$qId] == $expected) {
                return true;
            }
        }
        return false;  // Default false jika tidak match
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
        $affectedCount = $builder->countAllResults(false); // Count before update
        log_message('debug', "AnswerModel::batchUpdateStatus found {$affectedCount} rows to update");

        $builder->set('STATUS', $status);
        $result = $builder->update();

        $affectedRows = $this->db->affectedRows();
        log_message('debug', "AnswerModel::batchUpdateStatus result: " . ($result ? 'success' : 'failure') . ", affected rows: {$affectedRows}");

        return $result;
    }

    // public function getAnswersWithDetail($prodiId = null, $jenis = null)
    // {
    //     $builder = $this->db->table('answers a');
    //     $builder->select('
    //     a.id,
    //     q.question_text,
    //     a.answer_text,
    //     da.nama_lengkap,
    //     j.nama_jurusan,
    //     p.nama_prodi
    // ');
    //     $builder->join('questions q', 'q.id = a.question_id');
    //     $builder->join('detailaccount_alumni da', 'da.id_account = a.user_id');
    //     $builder->join('jurusan j', 'j.id = da.id_jurusan', 'left');
    //     $builder->join('prodi p', 'p.id = da.id_prodi', 'left');
    //     $builder->where('a.STATUS', 'completed'); // 🔹 fix disini

    //     if ($prodiId) {
    //         $builder->where('da.id_prodi', $prodiId);
    //     }

    //     if ($jenis === 'ami') {
    //         $builder->where('q.is_for_ami', 1);
    //     } elseif ($jenis === 'akreditasi') {
    //         $builder->where('q.is_for_accreditation', 1);
    //     }

    //     return $builder->get()->getResultArray();
    // }
    public function getAnswersRaw($prodiId = null, $questionId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detailaccount_alumni al');

        // LEFT JOIN answers agar semua alumni tetap muncul
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
        $builder->join('answers a', 'a.user_id = al.id_account AND a.status="completed"', 'left');
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
        $accountId       = $answer['user_id']; // di tabel answers namanya user_id

        // Hapus jawaban
        $this->delete($answerId);

        // Cek apakah masih ada jawaban untuk user & questionnaire ini
        $remaining = $this->where([
            'questionnaire_id' => $questionnaireId,
            'user_id'          => $accountId
        ])->countAllResults();

        if ($remaining === 0) {
            // Kalau tidak ada jawaban, hapus juga response
            $responseModel = new \App\Models\ResponseModel();
            $responseModel->where([
                'questionnaire_id' => $questionnaireId,
                'account_id'       => $accountId // di tabel responses pakai account_id
            ])->delete();
        }

        $db->transComplete();

        return $db->transStatus();
    }












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
