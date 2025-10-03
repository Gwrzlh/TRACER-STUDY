<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SectionModel;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use App\Models\MatrixRowModel;
use App\Models\MatrixColumnModels;
use App\Models\AnswerModel;
use App\Models\ResponseModel;

class SectionController extends BaseController
{
    public function index($questionnaire_id, $page_id)
    {
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();

        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->where('id', $page_id)->where('questionnaire_id', $questionnaire_id)->first();

        if (!$questionnaire || !$page) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

        $sections = $sectionModel->getSectionsWithQuestionCount($page_id);

        // Tambah status conditional
        foreach ($sections as &$section) {
            $section['conditional_status'] = $sectionModel->getConditionalStatus($section['id']);
        }

        return view('adminpage/questioner/section/index', [
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
            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages")
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

        return view('adminpage/questioner/section/create', [
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
            $logic_type = $this->request->getPost('logic_type');
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
                $conditionalLogic = json_encode([
                    'conditions' => $conditions,
                    'logic_type' => $logic_type
                ]);
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

        return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
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
            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
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

        return view('adminpage/questioner/section/edit', [
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

        // Tambahkan validasi kondisional jika logika diaktifkan
        if ($this->request->getPost('conditional_logic')) {
            $validation->setRules([
                'condition_question_id.*' => 'required|integer',  // Validasi setiap ID pertanyaan
                'operator.*' => 'required|in_list[is,is_not,contains,not_contains,greater,less]',
                'condition_value.*' => 'required'
            ]);
        }

        // Logging untuk debug POST data
        log_message('debug', '[SectionController::update] Received POST data: ' . json_encode($this->request->getPost()));

        if (!$validation->withRequest($this->request)->run()) {
            log_message('error', '[SectionController::update] Validation errors: ' . json_encode($validation->getErrors()));
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

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
                    // Terjemahkan ID opsi ke teks jika numerik
                    if (preg_match('/^\d+$/', $value)) {
                        $option = $optionModel->where(['question_id' => $conditionQuestionIds[$i], 'id' => $value])->first();
                        $value = $option ? $option['option_text'] : $value;
                        log_message('debug', "[SectionController::update] Translated option ID {$conditionValues[$i]} to text: $value");
                    }
                    $conditions[] = [
                        'field' => $conditionQuestionIds[$i],  // Standardisasi ke 'field' dalam JSON
                        'operator' => $operators[$i],
                        'value' => $value
                    ];
                }

            }
                if (!empty($conditions)) {
                    $conditionalLogic = json_encode([
                        'conditions' => $conditions,
                        'logic_type' => $logic_type
                    ]);
                }
          }

        $sectionModel = new SectionModel();
        $db = \Config\Database::connect();
        $db->transStart();  // Mulai transaksi untuk keamanan


        try {
            $sectionModel->update($section_id, [
                'section_title' => $this->request->getPost('section_title'),
                'section_description' => $this->request->getPost('section_description'),
                'show_section_title' => $this->request->getPost('show_section_title') ? 1 : 0,
                'show_section_description' => $this->request->getPost('show_section_description') ? 1 : 0,
                'order_no' => $this->request->getPost('order_no'),
                'conditional_logic' => $conditionalLogic
            ]);
            $db->transComplete();
            log_message('info', "[SectionController::update] Section {$section_id} updated successfully.");
            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
                            ->with('success', 'Section berhasil diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', "[SectionController::update] Error updating section {$section_id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui section: ' . $e->getMessage());
        }

    }

    public function delete($questionnaire_id, $page_id, $section_id)
    {
        $sectionModel      = new SectionModel();
        $questionModel     = new QuestionModel();
        $optionModel       = new QuestionOptionModel();
        $matrixRowModel    = new MatrixRowModel();
        $matrixColumnModel = new MatrixColumnModels();
        $answerModel       = new AnswerModel();
        $responseModel     = new ResponseModel(); // Hapus responses terkait questionnaire

        // Hapus semua responses terkait questionnaire
        $responseModel->where('questionnaire_id', $questionnaire_id)->delete();

        // Ambil semua pertanyaan di section
        $questions = $questionModel->where('section_id', $section_id)->findAll();

        foreach ($questions as $q) {
            $answerModel->where('question_id', $q['id'])->delete();
            $optionModel->where('question_id', $q['id'])->delete();
            $matrixRowModel->where('question_id', $q['id'])->delete();
            $matrixColumnModel->where('question_id', $q['id'])->delete();
        }

        // Hapus pertanyaan di section
        $questionModel->where('section_id', $section_id)->delete();

        // Hapus section
        $sectionModel->delete($section_id);

        return redirect()->to("/admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections")
            ->with('success', 'Section beserta semua relasinya berhasil dihapus.');
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
    public function duplicate($questionnaire_id, $page_id, $section_id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $sectionModel = new \App\Models\SectionModel();
            $questionModel = new \App\Models\QuestionModel();
            $questionOptionModel = new \App\Models\QuestionOptionModel();
            $matrixRowModel = new \App\Models\MatrixRowModel();
            $matrixColumnModel = new MatrixColumnModels(); // Corrected from MatrixColumnModels

            // Fetch original section
            $originalSection = $sectionModel->find($section_id);
            if (!$originalSection) {
                throw new \Exception('Section not found');
            }

            // Prepare new section data
            $newSectionData = [
                'questionnaire_id' => $originalSection['questionnaire_id'],
                'page_id' => $originalSection['page_id'],
                'section_title' => 'Copy of ' . $originalSection['section_title'],
                'section_description' => $originalSection['section_description'],
                'show_section_title' => $originalSection['show_section_title'],
                'show_section_description' => $originalSection['show_section_description'],
                'order_no' => $sectionModel->getNextOrderNo($page_id), // Assuming method exists as per prompt
                'conditional_logic' => null, // Reset
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Insert new section
            $newSectionId = $sectionModel->insert($newSectionData);
            if (!$newSectionId) {
                throw new \Exception('Failed to duplicate section');
            }
            log_message('info', 'Duplicated section: Original ID ' . $section_id . ' to New ID ' . $newSectionId);

            // Fetch questions for original section
            $originalQuestions = $questionModel->where('section_id', $section_id)->orderBy('order_no', 'ASC')->findAll();

            foreach ($originalQuestions as $originalQuestion) {
                // Prepare new question data (updated to match actual schema, reset conditions, set parent to null)
                $newQuestionData = [
                    'questionnaires_id' => $originalQuestion['questionnaires_id'],
                    'page_id' => $originalQuestion['page_id'],
                    'section_id' => $newSectionId,
                    'question_text' => $originalQuestion['question_text'],
                    'question_type' => $originalQuestion['question_type'],
                    'is_required' => $originalQuestion['is_required'],
                    'order_no' => $originalQuestion['order_no'], // Maintain relative order
                    'parent_question_id' => null, // Reset (assuming no nested handling needed)
                    'condition_value' => null, // Reset (condition-related)
                    'condition_json' => null, // Reset as per prompt
                    'scale_min' => $originalQuestion['scale_min'],
                    'scale_max' => $originalQuestion['scale_max'],
                    'scale_step' => $originalQuestion['scale_step'],
                    'scale_min_label' => $originalQuestion['scale_min_label'],
                    'scale_max_label' => $originalQuestion['scale_max_label'],
                    'allowed_types' => $originalQuestion['allowed_types'],
                    'max_file_size' => $originalQuestion['max_file_size'],
                    'user_field_name' => $originalQuestion['user_field_name'],
                    'is_for_ami' => $originalQuestion['is_for_ami'],
                    'is_for_accreditation' => $originalQuestion['is_for_accreditation'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Insert new question
                $newQuestionId = $questionModel->insert($newQuestionData);
                if (!$newQuestionId) {
                    throw new \Exception('Failed to duplicate question ID ' . $originalQuestion['id']);
                }
                log_message('info', 'Duplicated question: Original ID ' . $originalQuestion['id'] . ' to New ID ' . $newQuestionId . ' in section ' . $newSectionId);

                // Duplicate question options
                $originalOptions = $questionOptionModel->where('question_id', $originalQuestion['id'])->findAll();
                foreach ($originalOptions as $originalOption) {
                    $newOptionData = [
                        'question_id' => $newQuestionId,
                        'option_text' => $originalOption['option_text'],
                        'option_value' => $originalOption['option_value'],
                        'next_question_id' => null, // Reset
                        'order_number' => $originalOption['order_number']
                    ];
                    if (!$questionOptionModel->insert($newOptionData)) {
                        throw new \Exception('Failed to duplicate option for question ID ' . $newQuestionId);
                    }
                    log_message('info', 'Duplicated option for new question ID ' . $newQuestionId);
                }

                // Duplicate matrix rows (if applicable, e.g., for matrix-type questions)
                $originalRows = $matrixRowModel->where('question_id', $originalQuestion['id'])->findAll();
                foreach ($originalRows as $originalRow) {
                    $newRowData = [
                        'question_id' => $newQuestionId,
                        'row_text' => $originalRow['row_text'],
                        'order_no' => $originalRow['order_no']
                    ];
                    if (!$matrixRowModel->insert($newRowData)) {
                        throw new \Exception('Failed to duplicate matrix row for question ID ' . $newQuestionId);
                    }
                    log_message('info', 'Duplicated matrix row for new question ID ' . $newQuestionId);
                }

                // Duplicate matrix columns (if applicable)
                $originalColumns = $matrixColumnModel->where('question_id', $originalQuestion['id'])->findAll();
                foreach ($originalColumns as $originalColumn) {
                    $newColumnData = [
                        'question_id' => $newQuestionId,
                        'column_text' => $originalColumn['column_text'],
                        'order_no' => $originalColumn['order_no'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    if (!$matrixColumnModel->insert($newColumnData)) {
                        throw new \Exception('Failed to duplicate matrix column for question ID ' . $newQuestionId);
                    }
                    log_message('info', 'Duplicated matrix column for new question ID ' . $newQuestionId);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed during section duplication');
                return $this->response->setJSON(['success' => false, 'message' => 'Transaction failed']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Section duplicated successfully']);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Exception during duplication: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
