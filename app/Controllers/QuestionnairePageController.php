<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel; // Tambahkan model pertanyaan
use App\Models\QuestionOptionModel;
use App\Models\SectionModel;
use App\Models\MatrixColumnModels;
use App\Models\MatrixRowModel;
use App\Models\AnswerModel;
use App\Models\ResponseModel;

class QuestionnairePageController extends BaseController
{
    public function index($questionnaire_id)
    {
        $pageModel = new QuestionnairePageModel();

        // Ambil halaman sesuai questionnaire_id, urutkan berdasarkan order_no
        $pages = $pageModel
            ->where('questionnaire_id', $questionnaire_id)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        // Jika kuesioner baru dan belum ada halaman sama sekali, tetap tampilkan array kosong
        if (!$pages) {
            $pages = [];
        }

        $questionnaireModel = new QuestionnairModel();
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        return view('adminpage/questioner/page/index', [
            'pages' => $pages, // Sekarang akan kosong jika belum ada halaman sama sekali
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
            $conditionQuestionIds = $this->request->getPost('condition_question_id') ?? [];
            $operators = $this->request->getPost('operator') ?? [];
            $conditionValues = $this->request->getPost('condition_value') ?? [];
            $optionModel = new QuestionOptionModel();

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && isset($conditionValues[$i])) {
                    $value = $conditionValues[$i];
                    // Translate option ID ke option_text
                    if (preg_match('/^\d+$/', $value)) {
                        $option = $optionModel->where(['question_id' => $conditionQuestionIds[$i], 'id' => $value])->first();
                        $value = $option ? $option['option_text'] : $value;
                        log_message('debug', "[QuestionnairePageController::store] Translated option ID $conditionValues[$i] to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i], // Ganti question_id jadi field
                        'operator' => $operators[$i],
                        'value' => $value
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
            'created_by' => session()->get('id'), // Tambahkan ini
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages")
            ->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit($questionnaire_id, $page_id)
    {
        $pageModel = new QuestionnairePageModel();
        $page = $pageModel->find($page_id);

        if (!$page || $page['questionnaire_id'] != $questionnaire_id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
        }

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

        // Parse conditional logic: Transform to flat array for view
        $conditionalLogic = [];
        $logicType = 'all'; // Default
        if ($page['conditional_logic']) {
            $decoded = json_decode($page['conditional_logic'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $conditionalLogic = $decoded['conditions'] ?? [];
                $logicType = $decoded['logic_type'] ?? 'all';
                log_message('debug', "[QuestionnairePageController::edit] Transformed conditional logic: " . json_encode($conditionalLogic));
                log_message('debug', "[QuestionnairePageController::edit] Logic type: " . $logicType);
            } else {
                log_message('error', "[QuestionnairePageController::edit] Invalid JSON in conditional_logic: " . $page['conditional_logic']);
            }
        }

        return view('adminpage/questioner/page/edit', [
            'page' => $page,
            'questionnaire_id' => $questionnaire_id,
            'questions' => $questions,
            'operators' => $operators,
            'conditionalLogic' => $conditionalLogic, // Flat array: [0 => ['field' => '160', ...]]
            'logicType' => $logicType // Pass separately for select
        ]);
    }

    public function update($questionnaire_id, $page_id)
    {
        $pageModel = new QuestionnairePageModel();
        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $logicType = $this->request->getPost('logic_type') ?? 'all';
            $conditionQuestionIds = $this->request->getPost('condition_question_id') ?? [];
            $operators = $this->request->getPost('operator') ?? [];
            $conditionValues = $this->request->getPost('condition_value') ?? [];
            $optionModel = new QuestionOptionModel();

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && isset($conditionValues[$i])) {
                    $value = $conditionValues[$i];
                    // Translate option ID to option_text for select-type questions
                    $question = (new QuestionModel())->find($conditionQuestionIds[$i]);
                    if ($question && in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                        if (preg_match('/^\d+$/', $value)) {
                            $option = $optionModel->where(['question_id' => $conditionQuestionIds[$i], 'id' => $value])->first();
                            $value = $option ? $option['option_text'] : $value;
                            log_message('debug', "[QuestionnairePageController::update] Translated option ID {$conditionValues[$i]} to text: {$value}");
                        }
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i],
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode([
                    'conditions' => $conditions,
                    'logic_type' => $logicType
                ]);
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
        $pageModel         = new QuestionnairePageModel();
        $sectionModel      = new SectionModel();
        $questionModel     = new QuestionModel();
        $optionModel       = new QuestionOptionModel();
        $matrixRowModel    = new MatrixRowModel();
        $matrixColumnModel = new MatrixColumnModels();
        $answerModel       = new AnswerModel();
        $responseModel     = new ResponseModel(); // Hapus responses terkait questionnaire

        // Hapus semua responses terkait questionnaire
        $responseModel->where('questionnaire_id', $questionnaire_id)->delete();

        // Ambil semua pertanyaan di page
        $questions = $questionModel->where('page_id', $page_id)->findAll();

        foreach ($questions as $q) {
            $answerModel->where('question_id', $q['id'])->delete();
            $optionModel->where('question_id', $q['id'])->delete();
            $matrixRowModel->where('question_id', $q['id'])->delete();
            $matrixColumnModel->where('question_id', $q['id'])->delete();
        }

        // Hapus pertanyaan di page
        $questionModel->where('page_id', $page_id)->delete();

        // Hapus sections di page
        $sectionModel->where('page_id', $page_id)->delete();

        // Terakhir hapus page
        $pageModel->delete($page_id);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages")
            ->with('success', 'Page beserta semua relasinya berhasil dihapus.');
    }



    // Fungsi AJAX untuk mengambil opsi jawaban pertanyaan
    public function getQuestionOptions()
    {
        $question_id = $this->request->getGet('question_id');

        // Validasi question_id
        if (!$question_id) {
            return $this->response->setJSON(['type' => 'text', 'options' => []]);
        }

        $questionModel = new QuestionModel();
        $question = $questionModel->find($question_id);

        $options = [];
        $type = 'text';

        if ($question) {
            // Pastikan tipe pertanyaan sesuai
            if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $optionModel = new QuestionOptionModel();
                $options = $optionModel->select('id, option_text')->where('question_id', $question_id)->findAll();
                $type = 'select';
            }
        }

        // Format options untuk memastikan struktur JSON konsisten
        $formatted_options = array_map(function ($opt) {
            return [
                'id' => (string)$opt['id'], // Cast ke string untuk jaga-jaga
                'option_text' => $opt['option_text']
            ];
        }, $options);

        return $this->response->setJSON([
            'type' => $type,
            'options' => $formatted_options
        ]);
    }
}
