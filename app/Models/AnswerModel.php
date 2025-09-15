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
    protected $allowedFields    = ['questionnaire_id', 'user_id', 'question_id', 'answer_text', 'created_at'];

    protected bool $allowEmptyInserts = false;

    public function getStatus($questionnaire_id, $user_id)
    {
        $answers = $this->where(['questionnaire_id' => $questionnaire_id, 'user_id' => $user_id])->findAll();
        if (empty($answers)) return 'Belum Mengisi';
        $totalQuestions = (new QuestionModel())->where('questionnaires_id', $questionnaire_id)->countAllResults();
        return count($answers) < $totalQuestions ? 'On Going' : 'Finish';
    }

    public function getProgress($questionnaire_id, $user_id)
    {
        $answered = $this->where(['questionnaire_id' => $questionnaire_id, 'user_id' => $user_id])->countAllResults();
        $totalQuestions = (new QuestionModel())->where('questionnaires_id', $questionnaire_id)->countAllResults();
        return $totalQuestions > 0 ? ($answered / $totalQuestions) * 100 : 0;
    }

    public function getUserAnswers($questionnaire_id, $user_id)
    {
        $answers = $this->select('question_id, answer_text')->where(['questionnaire_id' => $questionnaire_id, 'user_id' => $user_id])->findAll();
        $result = [];
        foreach ($answers as $ans) {
            $result['q_' . $ans['question_id']] = $ans['answer_text'];
        }
        log_message('debug', '[getUserAnswers] Answers for q_id ' . $questionnaire_id . ': ' . print_r($result, true));
        return $result;
    }

    public function saveAnswer($user_id, $questionnaire_id, $question_id, $answer)
    {
        $data = [
            'user_id' => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id' => $question_id,
            'answer_text' => is_array($answer) ? json_encode($answer) : $answer,
            'created_at' => date('Y-m-d H:i:s'),
            'STATUS' => 'draft'
        ];
        $existing = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id, 'question_id' => $question_id])->first();
        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $this->insert($data);
        }
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
