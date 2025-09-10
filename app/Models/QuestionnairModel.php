<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionnairModel extends Model
{
    protected $table            = 'questionnaires';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'deskripsi', 'is_active', 'conditional_logic', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;

   public function checkConditions($conditions, $user_data, $previous_answers = [])
    {
        if (empty($conditions) || $conditions === null || $conditions === '') {
            log_message('debug', '[checkConditions] No conditions provided or empty, return true');
            return true;
        }
        $conditions = is_string($conditions) ? json_decode($conditions, true) : $conditions;
        if (!$conditions || !is_array($conditions)) {
            log_message('debug', '[checkConditions] Invalid JSON conditions or empty array, return true');
            return true;
        }

        $user_fields = [
            'email', 'username', 'group_id', 'nama_lengkap', 'nim', 'id_jurusan', 'id_prodi',
            'angkatan', 'ipk', 'alamat', 'alamat2', 'id_cities', 'kodepos', 'tahun_kelulusan',
            'jeniskelamin', 'notlp'
        ];
        $all_fields = array_merge($user_fields, array_keys($previous_answers));

        foreach ($conditions as $condition) {
            $field = trim($condition['field'] ?? '');
            $operator = $condition['operator'] ?? '';
            $value = trim($condition['value'] ?? '');

            if (empty($field) || !in_array($field, $all_fields)) {
                log_message('debug', "[checkConditions] Invalid or missing field: $field, skip");
                continue;
            }

            $data_value = $user_data[$field] ?? $previous_answers[$field] ?? null;
            if ($data_value === null) {
                log_message('debug', "[checkConditions] Field $field not found in user_data or answers, return false");
                return false;
            }

            $userValue = trim($data_value);
            log_message('debug', "[checkConditions] Comparing field=$field, userValue=$userValue, value=$value, operator=$operator");
            if ($operator === 'is' && $userValue !== $value) return false;
            if ($operator === 'is_not' && $userValue === $value) return false;
        }
        log_message('debug', '[checkConditions] All conditions passed, return true');
        return true;
    }

    public function getAccessibleQuestionnaires($user_data)
    {
        $all_q = $this->where('is_active', 'active')->findAll();
        $accessible = [];
        foreach ($all_q as $q) {
            if ($this->checkConditions($q['conditional_logic'], $user_data)) {
                $accessible[] = $q;
            }
        }
        log_message('debug', '[getAccessibleQuestionnaires] Accessible: ' . count($accessible));
        return $accessible;
    }

    public function getQuestionnaireStructure($q_id, $user_data, $previous_answers = [])
    {
        $q = $this->find($q_id);
        if (!$q) {
            log_message('error', '[getQuestionnaireStructure] Questionnaire not found: ' . $q_id);
            return null;
        }

        log_message('debug', '[getQuestionnaireStructure] User data: ' . print_r($user_data, true));
        log_message('debug', '[getQuestionnaireStructure] Previous answers: ' . print_r($previous_answers, true));

        $page_model = new QuestionnairePageModel();
        $section_model = new SectionModel();
        $question_model = new QuestionModel();
        $option_model = new QuestionOptionModel();
        $matrix_row_model = new MatrixRowModel();
        $matrix_column_model = new MatrixColumnModels();

        $pages = $page_model->where('questionnaire_id', $q_id)->orderBy('order_no', 'ASC')->findAll();
        log_message('debug', '[getQuestionnaireStructure] Pages found: ' . count($pages));

        $filtered_pages = [];
        foreach ($pages as $page) {
            if (!$this->checkConditions($page['conditional_logic'] ?? '', $user_data, $previous_answers)) {
                log_message('debug', "[getQuestionnaireStructure] Page {$page['id']} filtered out");
                continue;
            }

            $sections = $section_model->where('page_id', $page['id'])->orderBy('order_no', 'ASC')->findAll();
            log_message('debug', "[getQuestionnaireStructure] Sections for page {$page['id']}: " . count($sections));

            $filtered_sections = [];
            foreach ($sections as $section) {
                if (!$this->checkConditions($section['conditional_logic'] ?? '', $user_data, $previous_answers)) {
                    log_message('debug', "[getQuestionnaireStructure] Section {$section['id']} filtered out");
                    continue;
                }

                $questions = $question_model->where('section_id', $section['id'])->orderBy('order_no', 'ASC')->findAll();
                log_message('debug', "[getQuestionnaireStructure] Questions for section {$section['id']}: " . count($questions));

                $filtered_questions = [];
                foreach ($questions as $question) {
                    if (!$this->checkConditions($question['condition_json'] ?? '', $user_data, $previous_answers)) {
                        log_message('debug', "[getQuestionnaireStructure] Question {$question['id']} filtered out");
                        continue;
                    }
                    // Ambil opsi untuk dropdown/radio/checkbox
                    if (in_array(strtolower($question['question_type']), ['dropdown', 'select', 'radio', 'checkbox'])) {
                        $options = $option_model->where('question_id', $question['id'])->orderBy('order_number', 'ASC')->findAll();
                        $question['options'] = array_column($options, 'option_text');
                    } else {
                        $question['options'] = [];
                    }
                    // Ambil rows dan columns untuk matrix
                    if (strtolower($question['question_type']) === 'matrix') {
                        $question['matrix_rows'] = $matrix_row_model->where('question_id', $question['id'])->orderBy('order_no', 'ASC')->findAll();
                        $question['matrix_columns'] = $matrix_column_model->where('question_id', $question['id'])->orderBy('order_no', 'ASC')->findAll();
                        log_message('debug', "[getQuestionnaireStructure] Question {$question['id']} ({$question['question_text']}) matrix rows: " . count($question['matrix_rows']) . ", columns: " . count($question['matrix_columns']));
                    }
                    log_message('debug', "[getQuestionnaireStructure] Question {$question['id']} ({$question['question_text']}) options: " . print_r($question['options'], true));
                    $filtered_questions[] = $question;
                }
                if (!empty($filtered_questions)) {
                    $section['questions'] = $filtered_questions;
                    $filtered_sections[] = $section;
                }
            }
            if (!empty($filtered_sections)) {
                $page['sections'] = $filtered_sections;
                $filtered_pages[] = $page;
            }
        }

        log_message('debug', '[getQuestionnaireStructure] Filtered pages: ' . count($filtered_pages));
        return ['questionnaire' => $q, 'pages' => $filtered_pages];
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
