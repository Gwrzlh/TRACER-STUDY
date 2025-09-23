<?php

namespace App\Controllers\Questionnaire;;

use App\Controllers\BaseController;
use App\Models\Questionnaire\AnswerModel;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\Questionnaire\QuestionnairModel;
use App\Models\Questionnaire\QuestionModel;
use App\Models\Questionnaire\QuestionnairePageModel;
use App\Models\Questionnaire\QuestionnairConditionModel;
use App\Models\Organisasi\SectionModel;



class UserQuestionController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel = new AnswerModel();
    }

    public function index()
    {
        $user = session()->get('user');
        if (!$user || !$user['logged_in']) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($user);
        $data = [];
        foreach ($questionnaires as $q) {
            $status = $this->answerModel->getStatus($q['id'], $user['id']);
            $data[] = [
                'id' => $q['id'],
                'judul' => $q['title'],
                'status' => $status ?: 'Belum Mengisi'
            ];
        }
        return view('alumni/questionnaire/index', ['data' => $data]);
    }
}
