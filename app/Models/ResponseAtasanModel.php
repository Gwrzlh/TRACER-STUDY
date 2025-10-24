<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponseAtasanModel extends Model
{
    protected $table = 'responses_atasan';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_questionnaire',
        'id_account',
        'answers',
        'status',
        'progress',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'array';

    protected $validationRules = [
        'id_questionnaire' => 'required|integer',
        'id_account'       => 'required|integer',
        'status'           => 'in_list[draft,completed]',
        'progress'         => 'permit_empty|decimal'
    ];

    // Ambil jawaban responden
    public function getAnswers($q_id, $account_id)
    {
        $data = $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->first();

        return $data ? json_decode($data['answers'], true) ?? [] : [];
    }

    // Simpan atau update jawaban tanpa overwrite updated_at manual
    public function saveAnswers($q_id, $account_id, $answers)
    {
        $existing = $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->first();

        $json = json_encode($answers);

        if ($existing) {
            return $this->update($existing['id'], [
                'answers' => $json,
                'status'  => 'draft'
                // Hapus updated_at manual, biarkan CI handle
            ]);
        }

        return $this->insert([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id,
            'answers'          => $json,
            'status'           => 'draft'
        ]);
    }

    // Hitung progress pengisian
    public function calculateProgress($q_id, $account_id, $structure)
    {
        $answers = $this->getAnswers($q_id, $account_id);
        $total = count($structure);
        if ($total === 0) return 0;

        $filled = 0;
        foreach ($structure as $q) {
            $qid = $q['id'] ?? ($q['question_id'] ?? ($q['question']['id'] ?? null));
            if ($qid && isset($answers[$qid]) && $answers[$qid] !== '') $filled++;
        }

        $progress = round(($filled / $total) * 100, 2);

        $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->set('progress', $progress)->update();

        return $progress;
    }

    public function getStatus($q_id, $account_id)
    {
        $data = $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->first();

        return $data['status'] ?? 'draft';
    }

    public function isCompleted($q_id, $account_id)
    {
        $data = $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->first();

        return $data && $data['status'] === 'completed';
    }

    // Set status completed / draft tanpa overwrite updated_at manual
    public function setQuestionnaireCompleted($q_id, $account_id, $completed = true)
    {
        $status = $completed ? 'completed' : 'draft';

        return $this->where([
            'id_questionnaire' => $q_id,
            'id_account'       => $account_id
        ])->set('status', $status)
          ->update(); // updated_at otomatis
    }
}
