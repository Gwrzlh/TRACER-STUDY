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
    protected $allowedFields    = ['title', 'deskripsi', 'is_active', 'conditional_logic', 'created_at', 'updated_at', 'id_prodi'];

    protected bool $allowEmptyInserts = false;


    public function checkConditions($conditions, $user_data, $previous_answers = [], $forRole = 'alumni')
    {
        if (empty($conditions) || $conditions === null || $conditions === '' || $conditions === '[]') {
            log_message('debug', '[checkConditions] No conditions provided or empty, return true');
            return true;
        }

        $conditions = is_string($conditions) ? json_decode($conditions, true) : $conditions;
        if (!$conditions || !is_array($conditions)) {
            log_message('debug', '[checkConditions] Invalid JSON conditions or empty array, return true');
            return true;
        }

        log_message('debug', '[checkConditions] Evaluating conditions count: ' . count($conditions));

        $user_fields = [
            'email', 'username', 'group_id', 'nama_lengkap', 'nim', 'id_jurusan', 'id_prodi', 'angkatan', 'ipk',
            'alamat', 'alamat2', 'id_cities', 'kodepos', 'tahun_kelulusan', 'jeniskelamin', 'notlp'
        ];

        $all_fields = array_merge($user_fields, array_keys($previous_answers ?? []));

        foreach ($conditions as $condition) {
            $field = trim($condition['field'] ?? '');
            $operator = $condition['operator'] ?? '';
            $value = trim($condition['value'] ?? '');

            if ($forRole === 'kaprodi' && $field === 'id_prodi') {
                log_message('debug', "[checkConditions] Skipping id_prodi check for Kaprodi");
                continue;
            }

            if (empty($field) || !in_array($field, $all_fields)) {
                log_message('debug', "[checkConditions] Invalid or missing field: $field, skip");
                continue;
            }

            $data_value = null;
            if (preg_match('/^\d+$/', $field)) {
                $q_field = 'q_' . $field;
                $data_value = $user_data[$q_field] ?? $previous_answers[$q_field] ?? null;
                log_message('debug', "[checkConditions] Assumed question field: $field → $q_field");
            } else {
                $data_value = $user_data[$field] ?? $previous_answers[$field] ?? null;
            }

            if ($data_value === null) {
                log_message('debug', "[checkConditions] Field $field (or q_$field) not found/answered, return false");
                return false;
            }

            $userValue = trim(is_array($data_value) ? implode(',', $data_value) : $data_value);
            log_message('debug', "[checkConditions] Comparing field=$field, userValue='$userValue', value='$value', operator=$operator");

            $match = false;
            switch ($operator) {
                case 'is':
                    $match = ($userValue === $value);
                    break;
                case 'is_not':
                    $match = ($userValue !== $value);
                    break;
                case 'contains':
                    $match = (strpos($userValue, $value) !== false);
                    break;
                case 'not_contains':
                    $match = (strpos($userValue, $value) === false);
                    break;
                case 'greater':
                    $match = (floatval($userValue) > floatval($value));
                    break;
                case 'less':
                    $match = (floatval($userValue) < floatval($value));
                    break;
                default:
                    log_message('debug', "[checkConditions] Unknown operator $operator, assume false");
            }

            if (!$match) {
                log_message('debug', "[checkConditions] Condition failed for $field, return false");
                return false;
            }
        }

        log_message('debug', '[checkConditions] All conditions passed, return true');
        return true;
    }

    public function getAccessibleQuestionnaires($user_data, $role = null)
    {
        $builder = $this->db->table($this->table);

        // Filter kuesioner aktif
        $builder->where('is_active', 'active');

        // Filter prodi: ambil yang sesuai prodi atau prodi NULL (untuk semua)
        if (!empty($user_data['id_prodi'])) {
            $builder->groupStart()
                ->where('id_prodi', $user_data['id_prodi'])
                ->orWhere('id_prodi', null)
                ->groupEnd();
        }

        $all_q = $builder->get()->getResultArray();
        $accessible = [];

        foreach ($all_q as $q) {
            // Jika role kaprodi, abaikan conditional logic
            if ($role === 'kaprodi') {
                $accessible[] = $q;
                continue;
            }

            // Untuk role lain (misal alumni)
            // Jika conditional_logic kosong atau null → tetap akses
            $conditional = $q['conditional_logic'] ?? '';
            if (empty($conditional) || $conditional === '[]' || $this->checkConditions($conditional, $user_data)) {
                $accessible[] = $q;
            }
        }

        log_message('debug', '[DEBUG] Accessible Questionnaires for role=' . ($role ?? 'alumni') . ': ' . print_r($accessible, true));
        log_message('debug', '[getAccessibleQuestionnaires] Accessible count: ' . count($accessible));

        return $accessible;
    }




    public function getQuestionnaireStructure($q_id, $user_data, $previous_answers = [], $forRole = null)
    {
        $q = $this->find($q_id);
        if (!$q) {
            log_message('error', '[getQuestionnaireStructure] Questionnaire not found: ' . $q_id);
            return null;
        }

        $page_model          = new QuestionnairePageModel();
        $section_model       = new SectionModel();
        $question_model      = new QuestionModel();
        $option_model        = new QuestionOptionModel();
        $matrix_row_model    = new MatrixRowModel();
        $matrix_column_model = new MatrixColumnModels();

        $pages = $page_model->where('questionnaire_id', $q_id)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        $filtered_pages = [];
        foreach ($pages as $page) {

            if (
                !$this->checkConditions($page['conditional_logic'] ?? '', $user_data, $previous_answers)
                && $forRole !== 'kaprodi'
            ) {
                continue;
            }

            $sections = $section_model->where('page_id', $page['id'])
                ->orderBy('order_no', 'ASC')
                ->findAll();

            $filtered_sections = [];
            $all_questions     = [];

            foreach ($sections as $section) {

                if (
                    !$this->checkConditions($section['conditional_logic'] ?? '', $user_data, $previous_answers)
                    && $forRole !== 'kaprodi'
                ) {
                    continue;
                }

                $questions = $question_model->where('section_id', $section['id'])
                    ->orderBy('order_no', 'ASC')
                    ->findAll();

                $filtered_questions = [];
                foreach ($questions as $question) {

                    if (
                        !$this->checkConditions($question['condition_json'] ?? '', $user_data, $previous_answers)
                        && $forRole !== 'kaprodi'
                    ) {
                        continue;
                    }

                    // Ambil opsi untuk dropdown/radio/checkbox
                    if (in_array(strtolower($question['question_type']), ['dropdown', 'select', 'radio', 'checkbox'])) {
                        $options = $option_model->where('question_id', $question['id'])
                            ->orderBy('order_number', 'ASC')->findAll();
                        $question['options'] = array_column($options, 'option_text');
                    } else {
                        $question['options'] = [];
                    }

                    // Ambil matrix rows & columns jika tipe matrix
                    if (strtolower($question['question_type']) === 'matrix') {
                        $question['matrix_rows'] = $matrix_row_model->where('question_id', $question['id'])
                            ->orderBy('order_no', 'ASC')->findAll();
                        $question['matrix_columns'] = $matrix_column_model->where('question_id', $question['id'])
                            ->orderBy('order_no', 'ASC')->findAll();
                    } else {
                        $question['matrix_rows'] = [];
                        $question['matrix_columns'] = [];
                    }

                    log_message('debug', "[getQuestionnaireStructure] Question {$question['id']} options: " . print_r($question['options'], true));
                    log_message('debug', "[getQuestionnaireStructure] Question {$question['id']} matrix rows: " . count($question['matrix_rows']) . ", columns: " . count($question['matrix_columns']));

                    $filtered_questions[] = $question;
                }

                if (!empty($filtered_questions)) {
                    $section['questions'] = $filtered_questions;
                    $filtered_sections[]  = $section;
                    $all_questions = array_merge($all_questions, $filtered_questions);
                }
            }

            if (!empty($filtered_sections)) {
                $page['sections']    = $filtered_sections;
                $page['questions']   = $all_questions;
                $page['title']       = !empty($page['page_title']) ? $page['page_title'] : 'Halaman ' . $page['order_no'];
                $page['description'] = $page['page_description'] ?? '';
                $filtered_pages[] = $page;
            }
        }

        return [
            'questionnaire' => $q,
            'pages'         => $filtered_pages,
        ];
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
