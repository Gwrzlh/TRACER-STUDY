<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel; // Tambahkan model pertanyaan
use App\Models\QuestionOptionModel; 
use App\Models\SectionModel;

class QuestionnairePageController extends BaseController
{
    public function index($questionnaire_id)
    {
        $pageModel = new QuestionnairePageModel();
        $pages = $pageModel->where('questionnaire_id', $questionnaire_id)
                           ->orderBy('order_no', 'ASC')
                           ->findAll();

        $questionnaireModel = new QuestionnairModel();
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        return view('adminpage/questioner/page/index', [
            'pages' => $pages,
            'questionnaire' => $questionnaire
        ]);
    }

    public function create($questionnaire_id)
    {
        // Ambil semua pertanyaan untuk dropdown conditional logic
        $questionModel = new QuestionModel();
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)->findAll();
        
        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        return view('adminpage/questioner/page/create', [
            'questionnaire_id' => $questionnaire_id,
            'questions' => $questions,
            'operators' => $operators
        ]);
    }

    public function store($questionnaire_id)
    {
          $pageModel = new QuestionnairePageModel();
    $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
    $conditionalLogic = null;

    if ($conditionalLogicEnabled) {
        $logic_type = $this->request->getPost('logic_type');
        $conditionQuestionIds = $this->request->getPost('condition_question_id');
        $operators = $this->request->getPost('operator');
        $conditionValues = $this->request->getPost('condition_value');

        $conditions = [];
        for ($i = 0; $i < count($conditionQuestionIds); $i++) {
            if (!empty($conditionQuestionIds[$i])) {
                $conditions[] = [
                    'question_id' => $conditionQuestionIds[$i],
                    'operator' => $operators[$i],
                    'value' => $conditionValues[$i]
                ];
            }
        }
        
        if (!empty($conditions)) {
            $conditionalLogic = json_encode([
                'logic_type' => $logic_type,
                'conditions' => $conditions
            ]);
        }
    }
    
    $pageModel->insert([
        'questionnaire_id' => $questionnaire_id,
        'page_title' => $this->request->getPost('title'),
        'page_description' => $this->request->getPost('description'),
        'order_no' => $this->request->getPost('order_no'),
        'conditional_logic' => $conditionalLogic,
        'created_at' => date('Y-m-d H:i:s')
    ]);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages")
                         ->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit($questionnaire_id, $page_id)
    {
        $pageModel = new QuestionnairePageModel();
        $page = $pageModel->find($page_id);

        $questionModel = new QuestionModel();
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)->findAll();

        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];
        
        $conditionalLogic = [];
        if ($page['conditional_logic']) {
            $conditionalLogic = json_decode($page['conditional_logic'], true);
        }

        return view('adminpage/questioner/page/edit', [
            'page' => $page,
            'questionnaire_id' => $questionnaire_id,
            'questions' => $questions,
            'operators' => $operators,
            'conditionalLogic' => $conditionalLogic
        ]);
    }

    public function update($questionnaire_id, $page_id)
    {
        $pageModel = new QuestionnairePageModel();
        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $conditionQuestionIds = $this->request->getPost('condition_question_id');
            $operators = $this->request->getPost('operator');
            $conditionValues = $this->request->getPost('condition_value');

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && !empty($conditionValues[$i])) {
                    $conditions[] = [
                        'question_id' => $conditionQuestionIds[$i],
                        'operator' => $operators[$i],
                        'value' => $conditionValues[$i]
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

        $pageModel->update($page_id, [
            'page_title' => $this->request->getPost('title'),
            'page_description' => $this->request->getPost('description'),
            'order_no' => $this->request->getPost('order_no'),
            'conditional_logic' => $conditionalLogic,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages")
                         ->with('success', 'Halaman berhasil diperbarui.');
    }

    public function delete($questionnaire_id, $page_id)
    {

        // $questionnaireModel = new QuestionnairModel();
        $pageModel = new QuestionnairePageModel();
        $sectionModel = new SectionModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

       $questionOp = $questionModel->where('page_id', $page_id)->findAll();
        foreach ($questionOp as $q) {
            $optionModel->where('question_id', $q['id'])->delete();
        }

        $questionModel->where('page_id', $page_id)->delete();
        
        $sectionModel->where('page_id', $page_id)->delete();

        $pageModel->delete($page_id);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages")
                         ->with('success', 'Halaman berhasil dihapus.');
    }

    // Fungsi AJAX untuk mengambil opsi jawaban pertanyaan
   public function getQuestionOptions()
    {
        $question_id = $this->request->getGet('question_id');

        $questionModel = new QuestionModel();
        $question = $questionModel->find($question_id);

        $options = [];
        $type = 'text';

        // case 'dropdown':
        //     $optionModel = new QuestionOptionModel();
        //     $options = $optionModel->select('id, option_text')->where('question_id', $question_id)->findAll();
        //     $type = 'select';
        //     break;

        if ($question) {
            // Asumsi field 'question_type' pada tabel pertanyaan menunjukkan jenis input
            if ($question['question_type'] == 'radio' || $question['question_type'] == 'checkbox' || $question['question_type'] == 'dropdown') {
                $optionModel = new QuestionOptionModel();
                // Asumsi field 'option_text' menyimpan teks jawaban
                $options = $optionModel->select('id, option_text')->where('question_id', $question_id)->findAll();
                $type = 'select';
            }
        }
        
        return $this->response->setJSON([
            'type' => $type,
            'options' => $options
        ]);
    }
}