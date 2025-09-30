<?php

namespace App\Models\Questionnaire;

use CodeIgniter\Model;

class QuestionOptionModel extends Model
{
    protected $table            = 'question_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['question_id',
        'option_text', 
        'option_value',
        'next_question_id',
        'order_number'];



    protected bool $allowEmptyInserts = false;

    public function getQuestionOptions($questionId)
    {
        return $this->where('question_id', $questionId)
                   ->orderBy('order_number', 'ASC')
                   ->findAll();
    }
    
    // Get next question based on selected option
    public function getNextQuestion($questionId, $optionValue)
    {
        $option = $this->where('question_id', $questionId)
                      ->where('option_value', $optionValue)
                      ->first();
                      
        return $option ? $option['next_question_id'] : null;
    }
    
    // Check if question has conditional logic
    public function hasConditionalLogic($questionId)
    {
        $options = $this->where('question_id', $questionId)
                       ->where('next_question_id IS NOT NULL')
                       ->countAllResults();
                       
        return $options > 0;
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