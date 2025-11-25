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
        'id_alumni',
        'answers',
        'status',
        'progress',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    public function getAnswers($q_id, $account_id, $alumni_id)
    {
        $response = $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->first();
        return $response ? json_decode($response['answers'], true) : [];
    }

    public function saveAnswers($q_id, $account_id, $alumni_id, $answers)
    {
        $data = [
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id,
            'answers' => json_encode($answers),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $existing = $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->first();

        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status'] = 'draft';
            $data['progress'] = 0;
            $this->insert($data);
        }
    }

    public function calculateProgress($q_id, $account_id, $alumni_id, $structure)
    {
        $answers = $this->getAnswers($q_id, $account_id, $alumni_id);
        $totalQuestions = 0;
        $answered = 0;

        foreach ($structure['pages'] as $page) {
            foreach ($page['sections'] as $section) {
                foreach ($section['questions'] as $question) {
                    if ($question['is_required']) {
                        $totalQuestions++;
                        if (isset($answers[$question['id']]) && !empty($answers[$question['id']])) {
                            $answered++;
                        }
                    }
                }
            }
        }

        $progress = $totalQuestions > 0 ? ($answered / $totalQuestions) * 100 : 0;
        $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->set('progress', $progress)->update();

        return $progress;
    }

    public function getStatus($q_id, $account_id, $alumni_id)
    {
        $response = $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->first();

        return $response ? $response['status'] : 'draft';
    }

    public function isCompleted($q_id, $account_id, $alumni_id)
    {
        $response = $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->first();

        return $response && $response['status'] === 'completed';
    }

    public function setQuestionnaireCompleted($q_id, $account_id, $alumni_id, $completed = true)
    {
        $status = $completed ? 'completed' : 'draft';
        $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->set('status', $status)->update();
    }

    public function getAlumniAssessmentStatus($q_id, $account_id, $alumni_id)
    {
        $response = $this->where([
            'id_questionnaire' => $q_id,
            'id_account' => $account_id,
            'id_alumni' => $alumni_id
        ])->first();

        if (!$response) {
            return 'not_started';
        } elseif ($response['status'] === 'completed') {
            return 'completed';
        } else {
            return 'in_progress';
        }
    }
}