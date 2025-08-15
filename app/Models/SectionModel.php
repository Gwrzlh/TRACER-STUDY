<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table            = 'questionnaire_sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'questionnaire_id', 'page_id', 'section_title', 'section_description',
        'show_section_title', 'show_section_description', 'order_no'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

     public function getSectionsByPage($page_id)
    {
        return $this->where('page_id', $page_id)
                   ->orderBy('order_no', 'ASC')
                   ->findAll();
    }

    // Get sections with question count
    public function getSectionsWithQuestionCount($page_id)
    {
        return $this->select('questionnaire_sections.*, COUNT(questions.id) as question_count')
                   ->join('questions', 'questions.section_id = questionnaire_sections.id', 'left')
                   ->where('questionnaire_sections.page_id', $page_id)
                   ->groupBy('questionnaire_sections.id')
                   ->orderBy('questionnaire_sections.order_no', 'ASC')
                   ->findAll();
    }

    // Get next order number
    public function getNextOrderNo($page_id)
    {
        $lastOrder = $this->where('page_id', $page_id)
                         ->selectMax('order_no')
                         ->first();
        
        return ($lastOrder['order_no'] ?? 0) + 1;
    }

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
