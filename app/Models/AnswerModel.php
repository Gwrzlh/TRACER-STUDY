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
    protected $allowedFields    = ['questionnaire_id', 'user_id', 'question_id', 'answer_text', 'created_at', 'STATUS'];

    protected bool $allowEmptyInserts = false;

    // Di App/Models/AnswerModel.php
    public function getStatus($questionnaireId, $userId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('STATUS');
        $builder->where(['questionnaire_id' => $questionnaireId, 'user_id' => $userId]);
        $builder->limit(1);
        $query = $builder->get();
        $result = $query->getRowArray();

        $status = $result['STATUS'] ?? 'draft';
        log_message('debug', '[AnswerModel] getStatus for Q_ID: ' . $questionnaireId . ', User ID: ' . $userId . ' returned: ' . $status);
        return $status;
    }

    public function setStatus($questionnaireId, $userId, $status)
    {
        $data = ['STATUS' => $status];
        $updated = $this->where(['questionnaire_id' => $questionnaireId, 'user_id' => $userId])->set($data)->update();
        log_message('debug', '[AnswerModel] Set STATUS for Q_ID: ' . $questionnaireId . ', User ID: ' . $userId . ' to ' . $status . ' (updated rows: ' . $updated . ')');
        return $this->getStatus($questionnaireId, $userId);
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
                $result[$ans['question_id']] = $ans['answer_text'];
            } else {
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
        $now = Time::now('Asia/Jakarta', 'id_ID')->toDateTimeString();

        $data = [
            'user_id'          => $user_id,
            'questionnaire_id' => $questionnaire_id,
            'question_id'      => $question_id,
            'answer_text'      => is_array($answer) ? json_encode($answer) : $answer,
            'STATUS'           => 'draft',
            'created_at'       => $now,
        ];

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

        $questionModel = new \App\Models\QuestionModel();
        $totalQuestions = $questionModel->where('questionnaires_id', $questionnaire_id)->countAllResults();
        $answered = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])->countAllResults();

        if ($answered >= $totalQuestions && $totalQuestions > 0) {
            $toUpdate = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])->findAll();
            foreach ($toUpdate as $ans) {
                if (!empty($ans['id'])) {
                    $this->update($ans['id'], ['STATUS' => 'completed']);
                }
            }

            $lastAnswer = $this->where(['user_id' => $user_id, 'questionnaire_id' => $questionnaire_id])
                ->orderBy('created_at', 'DESC')
                ->first();

            $submittedAt = $lastAnswer['created_at'] ?? $now;

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
    // public function getAnswersWithDetail($prodiId = null, $jenis = null)
    // {
    //     $builder = $this->db->table('answers a');
    //     $builder->select('
    //     a.id,
    //     q.question_text,
    //     a.answer_text,
    //     da.nama_lengkap,
    //     j.nama_jurusan,
    //     p.nama_prodi
    // ');
    //     $builder->join('questions q', 'q.id = a.question_id');
    //     $builder->join('detailaccount_alumni da', 'da.id_account = a.user_id');
    //     $builder->join('jurusan j', 'j.id = da.id_jurusan', 'left');
    //     $builder->join('prodi p', 'p.id = da.id_prodi', 'left');
    //     $builder->where('a.STATUS', 'completed'); // 🔹 fix disini

    //     if ($prodiId) {
    //         $builder->where('da.id_prodi', $prodiId);
    //     }

    //     if ($jenis === 'ami') {
    //         $builder->where('q.is_for_ami', 1);
    //     } elseif ($jenis === 'akreditasi') {
    //         $builder->where('q.is_for_accreditation', 1);
    //     }

    //     return $builder->get()->getResultArray();
    // }
    public function getAnswersRaw($prodiId = null, $questionId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('detailaccount_alumni al');

        // LEFT JOIN answers agar semua alumni tetap muncul
        $builder->select("
        al.nama_lengkap as alumni_name,
        al.nim,
        al.tahun_kelulusan,
        j.nama_jurusan as jurusan_name,
        p.nama_prodi as prodi_name,
        a.answer_text,
        a.STATUS,
        q.id as question_id,
        q.question_text
    ");

        $builder->join('jurusan j', 'j.id = al.id_jurusan', 'left');
        $builder->join('prodi p', 'p.id = al.id_prodi', 'left');
        $builder->join('answers a', 'a.user_id = al.id_account AND a.status="completed"', 'left');
        $builder->join('questions q', 'q.id = a.question_id', 'left');

        if (!empty($prodiId)) {
            $builder->where('p.id', $prodiId);
        }

        if (!empty($questionId)) {
            $builder->where('q.id', $questionId);
        }

        $builder->orderBy('al.nama_lengkap', 'ASC');

        return $builder->get()->getResultArray();
    }
    public function deleteAnswerAndCheckResponse($answerId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $answer = $this->find($answerId);

        if (!$answer) {
            $db->transComplete();
            return false;
        }

        $questionnaireId = $answer['questionnaire_id'];
        $accountId       = $answer['user_id']; // di tabel answers namanya user_id

        // Hapus jawaban
        $this->delete($answerId);

        // Cek apakah masih ada jawaban untuk user & questionnaire ini
        $remaining = $this->where([
            'questionnaire_id' => $questionnaireId,
            'user_id'          => $accountId
        ])->countAllResults();

        if ($remaining === 0) {
            // Kalau tidak ada jawaban, hapus juga response
            $responseModel = new \App\Models\ResponseModel();
            $responseModel->where([
                'questionnaire_id' => $questionnaireId,
                'account_id'       => $accountId // di tabel responses pakai account_id
            ])->delete();
        }

        $db->transComplete();

        return $db->transStatus();
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
