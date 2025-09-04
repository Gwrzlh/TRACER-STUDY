<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table            = 'questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['questionnaires_id', 'page_id', 'section_id', 'question_text', 'question_type', 'is_required', 'order_no', 'condition_json', 'scale_min', 'scale_max', 'scale_step', 'scale_min_label', 'scale_max_label', 'allowed_types', 'max_file_size', 'matrix_rows', 'matrix_columns', 'matrix_options', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;

    protected $with = ['question_options'];

    protected function defineRelationships()
    {
        $this->hasMany('options', 'App\Models\QuestionOptionModel', 'question_id');
    }

    // Dates
    protected $useTimestamps = true;
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
