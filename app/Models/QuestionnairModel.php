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

    public function getAccessibleQuestionnaires($user_data)
    {
        $questionnaires = $this->findAll();
        $accessible = [];
        foreach ($questionnaires as $questionnaire) {
            if ($this->checkConditions($questionnaire['conditional_logic'], $user_data)) {
                $accessible[] = $questionnaire;
            }
        }
        return $accessible;
    }

    private function checkConditions($conditions, $user_data)
    {
        if (empty($conditions)) return true;
        $conditions = json_decode($conditions, true);
        if (!$conditions) return true;
        foreach ($conditions as $condition) {
            if ($condition['operator'] === 'is' && $user_data[$condition['field']] == $condition['value']) return true;
            if ($condition['operator'] === 'is_not' && $user_data[$condition['field']] != $condition['value']) return true;
        }
        return false;
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
