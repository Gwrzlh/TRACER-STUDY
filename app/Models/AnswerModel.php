<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class AnswerModel extends Model
{
    protected $table            = 'answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['questionnaire_id', 'user_id', 'question_id', 'answer_text', 'created_at', 'status'];

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
    public function getUserAnswers($questionnaire_id, $user_id, bool $forAdmin = false): array
    {
        $answers = $this->select('question_id, answer_text')
            ->where([
                'questionnaire_id' => $questionnaire_id,
                'user_id'          => $user_id
            ])
            ->findAll();

        $result = [];
        foreach ($answers as $ans) {
            if ($forAdmin) {
                // Untuk admin → simpan dengan key ID asli pertanyaan
                $result[$ans['question_id']] = $ans['answer_text'];
            } else {
                // Untuk alumni → tetap pakai prefix q_
                $result['q_' . $ans['question_id']] = $ans['answer_text'];
            }
        }

        log_message(
            'debug',
            '[getUserAnswers] (forAdmin=' . ($forAdmin ? 'true' : 'false') . ') ' .
                'Answers for q_id ' . $questionnaire_id . ': ' . print_r($result, true)
        );

        return $result;
    }



    public function saveAnswer($user_id, $questionnaire_id, $question_id, $answer)
    {
        // Simpan jawaban
        $now = Time::now('Asia/Jakarta')->toDateTimeString();

        $data = [
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id,
            'answer_text'      => is_array($answer) ? json_encode($answer) : $answer,
            'status'           => 'draft', // default sementara
            'created_at'       => $now,
        ];

        // Cek jawaban existing
        $existing = $this->where([
            'user_id' => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id' => $question_id
        ])->first();

        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $this->insert($data);
        }

        // Cek apakah kuesioner sudah selesai
        $questionModel = new \App\Models\QuestionModel();
        $totalQuestions = $questionModel->where('questionnaires_id', $questionnaire_id)->countAllResults();
        $answered = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])->countAllResults();

        if ($answered >= $totalQuestions && $totalQuestions > 0) {

            // Update semua jawaban menjadi completed
            $toUpdate = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])->findAll();
            foreach ($toUpdate as $ans) {
                if (!empty($ans['id'])) {
                    $this->update($ans['id'], ['status' => 'completed']);
                }
            }

            // Ambil waktu jawaban terakhir sebagai submitted_at
            $lastAnswer = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])
                ->orderBy('created_at', 'DESC')
                ->first();

            $submittedAt = $lastAnswer['created_at'] ?? $now;

            // Insert/update record di responses
            $responseModel = new \App\Models\ResponseModel();
            $existingResponse = $responseModel->where('account_id', $user_id)
                ->where('questionnaire_id', $questionnaire_id)
                ->first();

            $responseData = [
                'account_id'       => $user_id,
                'questionnaire_id' => $questionnaire_id,
                'submitted_at'     => $submittedAt,
                'status'           => 'completed',
                'ip_address'       => \Config\Services::request()->getIPAddress()
            ];

            if ($existingResponse) {
                $responseModel->update($existingResponse['id'], $responseData);
            } else {
                $responseModel->insert($responseData);
            }
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
