<?php

namespace App\Controllers\Questionnaire;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Questionnaire\QuestionnairConditionModel;
use App\Models\Questionnaire\QuestionModel;
use App\Models\Questionnaire\QuestionnairePageModel;

class QuestionnaireConditionController extends BaseController
{
    public function index($question_id)
    {
         $conditionModel = new QuestionnairConditionModel();
        $conditions = $conditionModel->where('question_id', $question_id)->findAll();

        $questionModel = new QuestionModel();
        $question = $questionModel->find($question_id);

        return view('adminpage/questionnaire_conditions/index', [
            'conditions' => $conditions,
            'question' => $question
        ]);
    }
    public function create($question_id)
    {
        $pageModel = new QuestionnairePageModel();
        $pages = $pageModel->findAll();

        return view('adminpage/questionnaire_conditions/create', [
            'question_id' => $question_id,
            'pages' => $pages
        ]);
    }
    public function store($question_id)
    {
        $conditionModel = new QuestionnairConditionModel();
        $conditionModel->insert([
            'question_id' => $question_id,
            'option_value' => $this->request->getPost('option_value'),
            'next_page_id' => $this->request->getPost('next_page_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to("/admin/questions/{$question_id}/conditions")
                         ->with('success', 'Kondisi berhasil ditambahkan.');
    }
}
