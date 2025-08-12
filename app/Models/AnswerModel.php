<?php

namespace App\Models;

use CodeIgniter\Model;

class AnswerModel extends Model
{
    protected $table            = 'answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['response_id',
        'question_id',
        'answer_text',
        'answer_value',
        'answered_at'];

    protected bool $allowEmptyInserts = false;

    public function saveAnswers($responseId, $answers)
    {
        $data = [];
        foreach ($answers as $questionId => $answer) {
            $data[] = [
                'response_id' => $responseId,
                'question_id' => $questionId,
                'answer_text' => is_array($answer) ? implode(', ', $answer) : $answer,
                'answer_value' => is_array($answer) ? implode(',', $answer) : $answer,
                'answered_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return $this->insertBatch($data);
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
