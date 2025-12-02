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
    protected $allowedFields    = ['questionnaire_id', 'user_id', 'question_id','alumni_id', 'answer_text', 'created_at', 'STATUS'];

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
        return $this->calculateProgress($questionnaire_id, $user_id);
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

    public function saveAnswer($user_id, $questionnaire_id, $question_id, $answer, $id_alumni_account = null)
    {
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        $data = [
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id,
            'answer_text'      => is_array($answer) ? json_encode($answer) : $answer,
            'STATUS'           => 'draft',
            'created_at'       => $now,
            'updated_at'       => $now,
        ];

        // Kalau atasan → tambah alumni_id
        if ($id_alumni_account !== null) {
            $data['alumni_id'] = $id_alumni_account;
        }

        log_message('debug', '[AnswerModel] saveAnswer | user_id: ' . $user_id . ' | q_id: ' . $questionnaire_id . ' | question_id: ' . $question_id . ' | alumni_id: ' . ($alumni_id ?? 'NULL'));

        // Query pencarian yang sesuai
        $builder = $this->where([
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id,
        ]);

        // Hanya tambah filter alumni_id jika ada (untuk atasan)
        if ($id_alumni_account !== null) {
            $builder->where('alumni_id', $id_alumni_account);
        }
        // KALAU ALUMNI → alumni_id = null → otomatis cari yang NULL → BENAR!
        // KALAU ATASAN → cari yang alumni_id = xxx → BENAR!

        $existing = $builder->first();

        if ($existing) {
            $this->update($existing['id'], $data);
            log_message('info', '[AnswerModel] Updated answer ID: ' . $existing['id']);
        } else {
            $this->insert($data);
            log_message('info', '[AnswerModel] Inserted new answer ID: ' . $this->insertID());
        }

        // === UPDATE RESPONSE (ALUMNI vs ATASAN) ===
        if ($id_alumni_account !== null) {
            // ATASAN → pakai responses_atasan
            $responseModel = new \App\Models\ResponseAtasanModel(); // pastikan nama modelnya benar
            $where = [
                'id_account'       => $user_id,
                'id_questionnaire' => $questionnaire_id,
                'id_alumni'        => $id_alumni_account,
            ];
        } else {
            // ALUMNI → pakai responses (tabel lama)
            $responseModel = new \App\Models\ResponseModel();
            $where = [
                'account_id'       => $user_id,
                'questionnaire_id' => $questionnaire_id,
            ];
        }

        $existingResponse = $responseModel->where($where)->first();

        $responseData = [
            'status'     => 'draft',
            'updated_at' => $now,
            'ip_address' => \Config\Services::request()->getIPAddress()
        ];

        if ($existingResponse) {
            if ($existingResponse['status'] !== 'completed') {
                $responseModel->update($existingResponse['id'], $responseData);
            }
        } else {
            $insertData = [
                'status'      => 'draft',
                'created_at'  => $now,
                'ip_address'  => \Config\Services::request()->getIPAddress()
            ];

            if ($id_alumni_account !== null) {
                // atasan
                $insertData += [
                    'id_account'       => $user_id,
                    'id_questionnaire' => $questionnaire_id,
                    'id_alumni'        => $id_alumni_account,
                ];
            } else {
                // alumni
                $insertData += [
                    'account_id'       => $user_id,
                    'questionnaire_id' => $questionnaire_id,
                ];
            }

            $responseModel->insert($insertData);
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

        $answers = $this->getAnswers($questionnaire_id, $user_id);
        $totalRelevantQuestions = 0;
        $answeredRelevantQuestions = 0;

        foreach ($structure['pages'] as $page) {
            $isPageRelevant = $this->evaluatePageConditions($page, $answers);

            if ($isPageRelevant) {
                foreach ($page['sections'] as $section) {
                    foreach ($section['questions'] as $question) {
                        $totalRelevantQuestions++;
                        if (isset($answers[$question['id']]) && !empty($answers[$question['id']])) {
                            $answeredRelevantQuestions++;
                        }
                    }
                }
            }
        }

        $isComplete = ($answeredRelevantQuestions >= $totalRelevantQuestions && $totalRelevantQuestions > 0);
        log_message('debug', '[validateLogicalCompletion] Answered/Relevant: ' . $answeredRelevantQuestions . '/' . $totalRelevantQuestions . ' - Complete: ' . ($isComplete ? 'YES' : 'NO'));
        return $isComplete;
    }

    // === METHOD BARU UNTUK MENDUKUNG CONDITIONAL LOGIC ===
    public function evaluatePageConditions($page, $previousAnswers)
    {
        $conditionsJson = $page['conditional_logic'] ?? '[]';
        log_message('debug', "[AnswerModel] Evaluating conditions for page_id: {$page['id']}, raw conditions: " . $conditionsJson);

        if (empty($conditionsJson) || $conditionsJson === '[]') {
            log_message('debug', "[AnswerModel] No conditions for page_id: {$page['id']}, returning true");
            return true;
        }

        try {
            $conditions = json_decode($conditionsJson, true);
            if (!$conditions || !is_array($conditions)) {
                log_message('error', "[AnswerModel] Invalid conditions JSON for page_id: {$page['id']}, JSON: " . $conditionsJson);
                return true; // Default to showing page if JSON is invalid
            }

            $logic_type = $conditions['logic_type'] ?? 'any';
            $conds = isset($conditions['conditions']) ? $conditions['conditions'] : $conditions;

            if (!is_array($conds) || empty($conds)) {
                log_message('debug', "[AnswerModel] No valid conditions for page_id: {$page['id']}, returning true");
                return true;
            }

            $pass = $logic_type === 'all' ? true : false;
            foreach ($conds as $condition) {
                $field = $condition['field'] ?? '';
                $operator = $condition['operator'] ?? '';
                $value = $condition['value'] ?? '';

                if (!$field || !$operator) {
                    log_message('warning', "[AnswerModel] Skipping invalid condition for page_id: {$page['id']}, field: {$field}, operator: {$operator}");
                    continue;
                }

                $answer = $previousAnswers[$field] ?? null;
                $answer_values = is_array(json_decode($answer, true)) ? json_decode($answer, true) : [$answer];

                if ($answer === null || empty($answer_values)) {
                    log_message('warning', "[AnswerModel] No answer found for field {$field} in page_id: {$page['id']}");
                    if ($logic_type === 'all') {
                        $pass = false;
                        break;
                    }
                    continue;
                }

                $match = false;
                $expected = strtolower($value);
                $answer_values_lower = array_map('strtolower', array_filter($answer_values));

                switch ($operator) {
                    case 'is':
                        $match = in_array($expected, $answer_values_lower);
                        break;
                    case 'is_not':
                        $match = !in_array($expected, $answer_values_lower);
                        break;
                    case 'contains':
                        $match = array_filter($answer_values_lower, fn($v) => strpos($v, $expected) !== false) ? true : false;
                        break;
                    case 'not_contains':
                        $match = array_filter($answer_values_lower, fn($v) => strpos($v, $expected) === false) ? true : false;
                        break;
                    case 'greater':
                        $match = array_filter($answer_values, fn($v) => is_numeric($v) && floatval($v) > floatval($value)) ? true : false;
                        break;
                    case 'less':
                        $match = array_filter($answer_values, fn($v) => is_numeric($v) && floatval($v) < floatval($value)) ? true : false;
                        break;
                    default:
                        log_message('warning', "[AnswerModel] Unknown operator {$operator} for field {$field} in page_id: {$page['id']}");
                }

                log_message('debug', "[AnswerModel] Condition result for field {$field}: operator={$operator}, expected={$value}, answer=" . json_encode($answer_values) . ", match={$match}");

                if ($logic_type === 'all') {
                    if (!$match) {
                        $pass = false;
                        break;
                    }
                } else {
                    if ($match) {
                        $pass = true;
                        break;
                    }
                }
            }

            log_message('debug', "[AnswerModel] Page_id {$page['id']} evaluation result: " . ($pass ? 'true' : 'false') . ", logic_type: {$logic_type}");
            return $pass;
        } catch (\Exception $e) {
            log_message('error', "[AnswerModel] Failed to parse conditions JSON for page_id: {$page['id']}, error: " . $e->getMessage());
            return true; // Default to showing page on error
        }
    }

    public function getNextPage($current_page_id, $questionnaire_id, $user_id)
    {
        $questionnaireModel = new \App\Models\QuestionnairModel();
        $structure = $questionnaireModel->getQuestionnaireStructure($questionnaire_id, $user_id);

        if (empty($structure['pages'])) {
            log_message('error', "[AnswerModel] No pages found for questionnaire_id: {$questionnaire_id}");
            return null;
        }

        $current_page_found = false;
        $user_answers = $this->getAnswers($questionnaire_id, $user_id);

        foreach ($structure['pages'] as $page) {
            if ($current_page_found && $this->evaluatePageConditions($page, $user_answers)) {
                log_message('debug', "[AnswerModel] Found next valid page: {$page['id']}");
                return $page['id'];
            }
            if ($page['id'] == $current_page_id) {
                $current_page_found = true;
            }
        }

        log_message('debug', "[AnswerModel] No valid next page found after page_id: {$current_page_id}");
        return null;
    }

    // === METHOD BARU UNTUK ATASAN KUESIONER ===
    public function getAnswers($questionnaireId, $userId, $alumniId = null)
    {
        $builder = $this->where([
            'questionnaire_id' => $questionnaireId,
            'user_id'          => $userId,
        ]);

        if ($alumniId !== null) {
            $builder->where('alumni_id', $alumniId);
        }

        $answers = $builder->findAll();

        $result = [];
        foreach ($answers as $ans) {
            $result['q_' . $ans['question_id']] = $ans['answer_text']; // PAKAI q_ !!!!
        }

        return $result;
    }

    public function saveAnswers($questionnaireId, $userId, $answers)
    {
        foreach ($answers as $questionId => $value) {
            $this->saveAnswer($userId, $questionnaireId, $questionId, $value);
        }
    }

    public function isCompleted($questionnaireId, $userId)
    {
        return $this->validateLogicalCompletion($questionnaireId, $userId);
    }

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

            foreach ($page['sections'] as $section) {
                foreach ($section['questions'] as $question) {
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
    // METHOD KHUSUS ATASAN 
   
  public function calculateProgressAtasan($q_id, $atasanAccountId, $alumniAccountId = null)
    {
        // AMBIL DATA ALUMNI DULU (INI YANG PENTING!)
        $alumniModel = new \App\Models\DetailaccountAlumni();
        $alumniDetail = $alumniModel->find($alumniAccountId); // atau sesuai model kamu

        // KALAU GAK ADA, cari dari detail_alumni berdasarkan id_account
        if (!$alumniDetail) {
            $detailModel = new \App\Models\DetailaccountAlumni();
            $alumniDetail = $detailModel->where('id_account', $alumniAccountId)->first();
        }

        // KALAU MASIH GAK ADA, return 0
        if (!$alumniDetail) {
            log_message('error', '[calculateProgressAtasan] Alumni detail tidak ditemukan untuk account ID: ' . $alumniAccountId);
            return 0;
        }

        // AMBIL JAWABAN ATASAN
        $userAnswers = $this->getAnswers($q_id, $atasanAccountId, $alumniAccountId);

        // GUNAKAN DATA ALUMNI UNTUK EVALUASI CONDITIONAL
        $structure = (new \App\Models\QuestionnairModel())
            ->getQuestionnaireStructure($q_id, $alumniDetail, $userAnswers, 'atasan');

        if (empty($structure) || empty($structure['pages'])) {
            log_message('error', '[calculateProgressAtasan] No structure or pages found for questionnaire ' . $q_id);
            return 0;
        }

        $totalRelevant = 0;
        $answeredRelevant = 0;

        foreach ($structure['pages'] as $page) {
            $pageVisible = $this->evaluatePageConditions($page, $userAnswers, $alumniDetail);
            if (!$pageVisible) continue;

            foreach ($page['sections'] as $section) {
                foreach ($section['questions'] as $question) {
                    $totalRelevant++;
                    if (isset($userAnswers['q_' . $question['id']]) && $userAnswers['q_' . $question['id']] !== '' && $userAnswers['q_' . $question['id']] !== null) {
                        $answeredRelevant++;
                    }
                }
            }
        }

        return $totalRelevant > 0 ? round(($answeredRelevant / $totalRelevant) * 100) : 0;
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