<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponseModel extends Model
{
    protected $table            = 'responses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['questionnaire_id',
        'account_id',
        'submitted_at',
        'status',
        'ip_address'];

    protected bool $allowEmptyInserts = false;

    public function getResponseWithAnswers($responseId)
    {
        return $this->db->table('responses r')
                       ->select('r.*, a.question_id, a.answer_text, a.answer_value, q.question_text')
                       ->join('answers a', 'r.id = a.response_id')
                       ->join('questions q', 'a.question_id = q.id')
                       ->where('r.id', $responseId)
                       ->get()
                       ->getResultArray();
    }
    
    // Check if alumni already responded
    public function hasResponded($questionnaireId, $accountId)
    {
        return $this->where('questionnaire_id', $questionnaireId)
                   ->where('account_id', $accountId)
                   ->where('status', 'completed')
                   ->countAllResults() > 0;
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
