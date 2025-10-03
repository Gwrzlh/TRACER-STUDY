<?php

namespace App\Models;

use CodeIgniter\Model;

class KuesionerModel extends Model
{
    protected $table = 'questionnaires'; // Nama tabel kuesioner atasan
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'title', 'is_active', 'conditional_logic', 'announcement'
    ];

    /**
     * Ambil semua kuesioner yang bisa diakses user
     */
    public function getAccessibleQuestionnaires($userData)
    {
        // Ambil semua kuesioner aktif
        $query = $this->where('is_active', 'active')->findAll();

        $accessible = [];
        foreach ($query as $q) {
            if ($this->checkConditions($q['conditional_logic'] ?? '', $userData)) {
                $accessible[] = $q;
            }
        }

        return $accessible;
    }

    /**
     * Ambil struktur kuesioner lengkap per halaman, sections, questions
     * $previousAnswers = jawaban sebelumnya user
     */
public function getQuestionnaireStructure($q_id, $userData, $previousAnswers = [])
{
    $questionnaire = $this->find($q_id);

    if (!$questionnaire) {
        return [
            'questionnaire' => null,
            'pages' => []
        ];
    }

    // ambil pages
    $pages = $this->db->table('questionnaire_pages')
        ->where('questionnaire_id', $q_id)
        ->orderBy('order_no', 'ASC')
        ->get()->getResultArray();

    $structure = [
        'questionnaire' => $questionnaire,
        'pages' => []
    ];

    foreach ($pages as $p) {
        // ambil sections per page
        $sections = $this->db->table('questionnaire_sections')
            ->where('page_id', $p['id'])
            ->get()->getResultArray();

        foreach ($sections as &$s) {
            // ambil questions per section
            $s['questions'] = $this->db->table('questions')
                ->where('section_id', $s['id'])
                ->get()->getResultArray();

            // cek tiap question
            foreach ($s['questions'] as &$q) {
                if (strtolower($q['question_type']) === 'matrix') {
                    // ambil matrix columns & rows
                    $q['matrix_columns'] = $this->db->table('matrix_columns')
                        ->where('question_id', $q['id'])
                        ->orderBy('id', 'ASC')
                        ->get()->getResultArray();

                    $q['matrix_rows'] = $this->db->table('matrix_rows')
                        ->where('question_id', $q['id'])
                        ->orderBy('id', 'ASC')
                        ->get()->getResultArray();
                } else {
                    // kalau tipe dropdown/radio/checkbox → ambil dari question_options
                    $q['options'] = $this->db->table('question_options')
                        ->where('question_id', $q['id'])
                        ->orderBy('order_number', 'ASC')
                        ->get()->getResultArray();
                }
            }
        }

        $structure['pages'][] = [
            'id' => $p['id'],
            'page_title' => $p['page_title'],
            'page_description' => $p['page_description'],
            'order_no' => $p['order_no'],
            'conditional_logic' => $p['conditional_logic'],
            'sections' => $sections
        ];
    }

    return $structure;
}



    /**
     * Cek apakah user memenuhi conditional logic
     * Conditional logic berupa JSON
     */
    public function checkConditions($conditionalLogic, $userData = [])
    {
        if (empty($conditionalLogic)) return true;

        $decoded = json_decode($conditionalLogic, true);
        if (!$decoded || !isset($decoded['conditions'])) return true;

        $conditions = $decoded['conditions'] ?? [];
        $logicType = $decoded['logic_type'] ?? 'all';
        $pass = ($logicType === 'all') ? true : false;

        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '';
            $value = $condition['value'] ?? '';

            $userValue = $userData[$field] ?? null;

            $match = false;
            switch ($operator) {
                case 'is':
                    $match = ($userValue == $value);
                    break;
                case 'is_not':
                    $match = ($userValue != $value);
                    break;
                case 'greater':
                    $match = is_numeric($userValue) && $userValue > $value;
                    break;
                case 'less':
                    $match = is_numeric($userValue) && $userValue < $value;
                    break;
                case 'contains':
                    $match = is_array($userValue) ? in_array($value, $userValue) : false;
                    break;
                case 'not_contains':
                    $match = is_array($userValue) ? !in_array($value, $userValue) : true;
                    break;
            }

            if ($logicType === 'all' && !$match) {
                return false;
            }
            if ($logicType === 'any' && $match) {
                return true;
            }
        }

        return ($logicType === 'all') ? true : false;
    }
}
