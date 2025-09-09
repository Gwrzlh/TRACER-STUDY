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

        // daftar field yang valid
        $user_fields = [
            'email',
            'username',
            'group_id',
            'nama_lengkap',
            'nim',
            'id_jurusan',
            'id_prodi',
            'angkatan',
            'ipk',
            'alamat',
            'alamat2',
            'id_cities',
            'kodepos',
            'tahun_kelulusan',
            'jeniskelamin',
            'notlp'
        ];

        foreach ($conditions as $condition) {
            $field    = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? null;
            $value    = $condition['value'] ?? null;

            // kalau field tidak valid, skip
            if (!in_array($field, $user_fields)) {
                continue;
            }

            // kalau field tidak ada di session user_data, skip
            if (!isset($user_data[$field])) {
                continue;
            }

            $userValue = $user_data[$field];

            if ($operator === 'is' && $userValue == $value) {
                return true;
            }
            if ($operator === 'is_not' && $userValue != $value) {
                return true;
            }
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
