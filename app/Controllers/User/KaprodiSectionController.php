<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\Questionnaire\SectionModel;
use App\Models\Questionnaire\QuestionnairePageModel;
use App\Models\Questionnaire\QuestionnairModel;
use App\Models\Questionnaire\QuestionModel;
use App\Models\Questionnaire\QuestionOptionModel;

class KaprodiSectionController extends BaseController
{
    public function index($questionnaire_id, $page_id)
    {
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();

        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->where('id', $page_id)->where('questionnaire_id', $questionnaire_id)->first();

        if (!$questionnaire || !$page) {
            return redirect()->to('kaprodi/kuesioner')->with('error', 'Data tidak ditemukan.');
        }

        $sections = $sectionModel->getSectionsWithQuestionCount($page_id);

        // Tambah status conditional
        foreach ($sections as &$section) {
            $section['conditional_status'] = $sectionModel->getConditionalStatus($section['id']);
        }

        return view('kaprodi/kuesioner/section/index', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'sections' => $sections,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id
        ]);
    }

    public function create($questionnaire_id, $page_id)
    {
        $questionnaireModel = new QuestionnairModel();
        $pageModel = new QuestionnairePageModel();
        $sectionModel = new SectionModel();
        $questionModel = new QuestionModel(); // Tambah untuk conditional

        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->find($page_id);

        if (!$questionnaire || !$page) {
            return redirect()->to("kaprodi/kuesioner/{$questionnaire_id}/pages")
                ->with('error', 'Data tidak ditemukan.');
        }

        $nextOrder = $sectionModel->getNextOrderNo($page_id);
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)->findAll(); // Tambah untuk conditional
        $operators = [ // Tambah untuk conditional
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        return view('kaprodi/kuesioner/section/create', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'next_order' => $nextOrder,
            'questions' => $questions,
            'operators' => $operators
        ]);
    }

    public function store($questionnaire_id, $page_id)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'section_title' => 'required|min_length[3]|max_length[255]',
            'section_description' => 'permit_empty|max_length[1000]',
            'show_section_title' => 'permit_empty|in_list[0,1]',
            'show_section_description' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $conditionQuestionIds = $this->request->getPost('condition_question_id') ?? [];
            $operators = $this->request->getPost('operator') ?? [];
            $conditionValues = $this->request->getPost('condition_value') ?? [];
            $optionModel = new QuestionOptionModel(); // Untuk ambil option_text

            $conditions = [];
            for ($i = 0; $i < count($conditionQuestionIds); $i++) {
                if (!empty($conditionQuestionIds[$i]) && !empty($operators[$i]) && isset($conditionValues[$i])) {
                    $value = $conditionValues[$i];
                    // Jika value adalah option ID (numeric), translate ke option_text
                    if (preg_match('/^\d+$/', $value)) {
                        $option = $optionModel->where(['question_id' => $conditionQuestionIds[$i], 'id' => $value])->first();
                        $value = $option ? $option['option_text'] : $value; // Fallback ke ID kalau gagal
                        log_message('debug', "[SectionController::store] Translated option ID $conditionValues[$i] to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i], // Ganti question_id jadi field
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

        $sectionModel = new SectionModel();
        $sectionModel->insert([
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_title' => $this->request->getPost('section_title'),
            'section_description' => $this->request->getPost('section_description'),
            'show_section_title' => $this->request->getPost('show_section_title') ? 1 : 0,
            'show_section_description' => $this->request->getPost('show_section_description') ? 1 : 0,
            'order_no' => $this->request->getPost('order_no'),
            'conditional_logic' => $conditionalLogic
        ]);

        return redirect()->to("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections")
            ->with('success', 'Section berhasil ditambahkan.');
    }

    public function edit($questionnaire_id, $page_id, $section_id)
    {
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();
        $questionModel = new QuestionModel(); // Tambah untuk conditional

        $section = $sectionModel->find($section_id);
        $page = $pageModel->find($page_id);
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        if (!$section || !$page || !$questionnaire) {
            return redirect()->to("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections")
                ->with('error', 'Data tidak ditemukan.');
        }

        $conditionalLogic = $section['conditional_logic'] ? json_decode($section['conditional_logic'], true) : [];
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)->findAll(); // Tambah untuk conditional
        $operators = [ // Tambah untuk conditional
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        return view('kaprodi/kuesioner/section/edit', [
            'questionnaire' => $questionnaire,
            'page' => $page,
            'section' => $section,
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_id' => $section_id,
            'questions' => $questions,
            'operators' => $operators,
            'conditionalLogic' => $conditionalLogic
        ]);
    }

    public function update($questionnaire_id, $page_id, $section_id)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'section_title' => 'required|min_length[3]|max_length[255]',
            'section_description' => 'permit_empty|max_length[1000]',
            'show_section_title' => 'permit_empty|in_list[0,1]',
            'show_section_description' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
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
                        log_message('debug', "[SectionController::update] Translated option ID $conditionValues[$i] to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i], // Ganti question_id jadi field
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

        $sectionModel = new SectionModel();
        $sectionModel->update($section_id, [
            'section_title' => $this->request->getPost('section_title'),
            'section_description' => $this->request->getPost('section_description'),
            'show_section_title' => $this->request->getPost('show_section_title') ? 1 : 0,
            'show_section_description' => $this->request->getPost('show_section_description') ? 1 : 0,
            'order_no' => $this->request->getPost('order_no'),
            'conditional_logic' => $conditionalLogic
        ]);

        return redirect()->to("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections")
            ->with('success', 'Section berhasil diperbarui.');
    }

    public function delete($questionnaire_id, $page_id, $section_id)
    {
        $sectionModel = new SectionModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $questions = $questionModel->where('section_id', $section_id)->findAll();

        foreach ($questions as $q) {
            $optionModel->where('question_id', $q['id'])->delete();
        }

        $questionModel->where('section_id', $section_id)->delete();

        $sectionModel->delete($section_id);

        return redirect()->to("kaprodi/kuesioner/{$questionnaire_id}/pages/{$page_id}/sections")
            ->with('success', 'Section berhasil dihapus.');
    }

    // Tambah method baru untuk duplicate
    public function duplicate($questionnaire_id, $page_id, $section_id)
    {
        $sectionModel = new SectionModel();
        $section = $sectionModel->find($section_id);

        if (!$section) {
            return $this->response->setJSON(['success' => false, 'message' => 'Section not found']);
        }

        $newSection = $section;
        unset($newSection['id']);
        $newSection['section_title'] = 'Copy of ' . $section['section_title'];
        $newSection['order_no'] = $sectionModel->getNextOrderNo($page_id);
        $sectionModel->insert($newSection);

        return $this->response->setJSON(['success' => true]);
    }

    // Tambah method untuk move up
    public function moveUp($questionnaire_id, $page_id, $section_id)
    {
        try {
            $sectionModel = new SectionModel();
            $section = $sectionModel->find($section_id);

            log_message('debug', 'MoveUp: section_id=' . $section_id . ', section=' . json_encode($section));

            if (!$section || $section['page_id'] != $page_id || $section['questionnaire_id'] != $questionnaire_id) {
                return $this->response->setJSON(['success' => false, 'message' => 'Section tidak ditemukan']);
            }

            if ($section['order_no'] > 1) {
                $prevSection = $sectionModel->where('page_id', $page_id)
                    ->where('order_no', $section['order_no'] - 1)
                    ->first();
                if ($prevSection) {
                    $sectionModel->update($section_id, ['order_no' => $section['order_no'] - 1]);
                    $sectionModel->update($prevSection['id'], ['order_no' => $prevSection['order_no'] + 1]);
                    return $this->response->setJSON(['success' => true]);
                }
            }
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak bisa memindahkan ke atas']);
        } catch (\Exception $e) {
            log_message('error', 'MoveUp Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    public function moveDown($questionnaire_id, $page_id, $section_id)
    {
        try {
            $sectionModel = new SectionModel();
            $section = $sectionModel->find($section_id);
            $maxOrder = $sectionModel->where('page_id', $page_id)->countAllResults();

            log_message('debug', 'MoveDown: section_id=' . $section_id . ', section=' . json_encode($section));

            if (!$section || $section['page_id'] != $page_id || $section['questionnaire_id'] != $questionnaire_id) {
                return $this->response->setJSON(['success' => false, 'message' => 'Section tidak ditemukan']);
            }

            if ($section['order_no'] < $maxOrder) {
                $nextSection = $sectionModel->where('page_id', $page_id)
                    ->where('order_no', $section['order_no'] + 1)
                    ->first();
                if ($nextSection) {
                    $sectionModel->update($section_id, ['order_no' => $section['order_no'] + 1]);
                    $sectionModel->update($nextSection['id'], ['order_no' => $nextSection['order_no'] - 1]);
                    return $this->response->setJSON(['success' => true]);
                }
            }
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak bisa memindahkan ke bawah']);
        } catch (\Exception $e) {
            log_message('error', 'MoveDown Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }
}
