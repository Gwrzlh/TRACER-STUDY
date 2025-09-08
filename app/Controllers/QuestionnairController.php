<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuestionModel;
use App\Models\QuestionOptionModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\QuestionnairModel;
use App\Models\QuestionnairePageModel;
use App\Models\SectionModel;
use App\Models\Prodi;
use App\Models\Jurusan;
// use App\Models\Cities;
use App\Models\Provincies;
use App\Models\Roles;
use App\Models\QuestionnairConditionModel;
use App\Models\MatrixRowModel;
use App\Models\MatrixColumnModels;
use Config\App;

class QuestionnairController extends BaseController
{
    public function index()
    {
        $model = new QuestionnairModel();
        $data = [
            'questionnaires' => $model->findAll()
        ];

        return view('adminpage/questioner/index', $data);
    }

    public function create()
    {
        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        $user_fields = [
            'email', 'username', 'group_id', 'display_name',
            'academic_nim', 'academic_faculty', 'academic_program',
            'academic_year', 'academic_ipk', 'street_1', 'street_2',
            'city', 'state_code',
            'academic_graduate_year', 'jenis_kel', 'HP'
        ];

        return view('adminpage/questioner/tambah', [
            'fields' => $user_fields,
            'operators' => $operators
        ]);
    }

    // Mendapatkan opsi dinamis untuk conditional logic
    public function getConditionalOptions()
    {
        $field = $this->request->getGet('field');
        $options = [];
        $type = 'text';

        switch ($field) {
            case 'academic_faculty':
                $facultyModel = new Jurusan();
                $options = $facultyModel->select('id, nama_jurusan as name')->findAll();
                $type = 'select';
                break;
            case 'academic_program':
                $programModel = new Prodi();
                $options = $programModel->select('id, nama_prodi as name')->findAll();
                $type = 'select';
                break;
            case 'jenis_kel':
                $options = [['id' => 'L', 'name' => 'Laki-laki'], ['id' => 'P', 'name' => 'Perempuan']];
                $type = 'select';
                break;
            case 'academic_year':
            case 'academic_graduate_year':
                $options = [];
                for ($i = date('Y'); $i >= 2000; $i--) {
                    $options[] = ['id' => (string)$i, 'name' => (string)$i];
                }
                $type = 'select';
                break;
            case 'city':
                $cityModel = new Provincies();
                $options = $cityModel->select('id, name')->findAll();
                $type = 'select';
                break;
            case 'group_id':
                $groupModel = new Roles();
                $options = $groupModel->select('id, nama as name')->findAll();
                $type = 'select';
                break;
        }

        return $this->response->setJSON([
            'type' => $type,
            'options' => $options
        ]);
    }

    // Menyimpan Kuesioner Baru
    public function store()
    {
        $questionnaireModel = new QuestionnairModel();

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('deskripsi');
        $status = $this->request->getPost('status');
        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');

        $conditionalLogic = null;
        if ($conditionalLogicEnabled) {
            $fields = $this->request->getPost('field_name');
            $operators = $this->request->getPost('operator');
            $values = $this->request->getPost('value');

            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[$i]) && !empty($operators[$i]) && !empty($values[$i])) {
                    $conditions[] = [
                        'field' => $fields[$i],
                        'operator' => $operators[$i],
                        'value' => $values[$i]
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

        $questionnaireModel->insert([
            'title' => $title,
            'deskripsi' => $description,
            'status' => $status,
            'conditional_logic' => $conditionalLogic,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('/admin/questionnaire'))->with('success', 'Kuesioner berhasil dibuat!');
    }

    // Halaman Edit Kuesioner
    public function edit($questionnaire_id)
    {
        $model = new QuestionnairModel();
        $questionnaire = $model->find($questionnaire_id);

        if (!$questionnaire) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            'contains' => 'Contains',
            'not_contains' => 'Not Contains',
            'greater' => 'Greater Than',
            'less' => 'Less Than'
        ];

        $user_fields = [
            'email', 'username', 'group_id', 'display_name',
            'academic_nim', 'academic_faculty', 'academic_program',
            'academic_year', 'academic_ipk', 'street_1', 'street_2',
            'city', 'state_code',
            'academic_graduate_year', 'jenis_kel', 'HP'
        ];

        $conditionalLogic = [];
        if ($questionnaire['conditional_logic']) {
            $conditionalLogic = json_decode($questionnaire['conditional_logic'], true);
        }

        return view('adminpage/questioner/edit', [
            'questionnaire' => $questionnaire,
            'conditionalLogic' => $conditionalLogic,
            'fields' => $user_fields,
            'operators' => $operators
        ]);
    }

    // Memperbarui Kuesioner
    public function update($questionnaire_id)
    {
        $model = new QuestionnairModel();
        $questionnaire = $model->find($questionnaire_id);

        if (!$questionnaire) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'deskripsi' => 'permit_empty|max_length[1000]',
            'status' => 'permit_empty|in_list[active,draft,inactive]',
            'conditional_logic' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $conditionalLogicEnabled = $this->request->getPost('conditional_logic');
        $conditionalLogic = null;

        if ($conditionalLogicEnabled) {
            $fields = $this->request->getPost('field_name');
            $operators = $this->request->getPost('operator');
            $values = $this->request->getPost('value');

            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[$i]) && !empty($operators[$i]) && !empty($values[$i])) {
                    $conditions[] = [
                        'field' => $fields[$i],
                        'operator' => $operators[$i],
                        'value' => $values[$i]
                    ];
                }
            }

            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

       $model->update($questionnaire_id, [
    'title' => $this->request->getPost('title'),
    'deskripsi' => $this->request->getPost('deskripsi'),
    'is_active' => $this->request->getPost('is_active'),
    'conditional_logic' => $conditionalLogic,
    'updated_at' => date('Y-m-d H:i:s')
]);


        return redirect()->to('admin/questionnaire')->with('success', 'Kuesioner berhasil diperbarui!');
    }
    public function delete($id)
    {
        $questionnaireModel = new QuestionnairModel();
        $pageModel = new QuestionnairePageModel();
        $sectionModel = new SectionModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        // Ambil semua pertanyaan dari questionnaire ini
        $questions = $questionModel->where('questionnaires_id', $id)->findAll();

        // Loop hapus option setiap pertanyaan
        foreach ($questions as $q) {
            $optionModel->where('question_id', $q['id'])->delete();
        }

        // Hapus pertanyaan
        $questionModel->where('questionnaires_id', $id)->delete();

        // Hapus section
        $sectionModel->where('questionnaire_id', $id)->delete();

        // Hapus page
        $pageModel->where('questionnaire_id', $id)->delete();

        // Terakhir hapus questionnaire
        $questionnaireModel->delete($id);

        return redirect()->to('admin/questionnaire')->with('success', 'Data dan relasinya berhasil dihapus.');
    }


     // Preview questionnaire for testing

    public function preview($questionnaire_id)
    {
        $questionnairModel = new QuestionnairModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $questionnaire = $questionnairModel->find($questionnaire_id);
        $questions = $questionModel->where('questionnaires_id', $questionnaire_id)
                                  ->orderBy('order_no', 'ASC')
                                  ->findAll();

        // Get options for each question
        foreach ($questions as &$q) {
            if (in_array($q['question_type'], ['radio','checkbox','dropdown'])) {
                $q['options'] = $optionModel->where('question_id', $q['id'])->findAll();
            }
        }

        return view('adminpage/questioner/preview', [
            'questionnaire' => $questionnaire,
            'questions' => $questions
        ]);
    }

    // TAMBAH method baru untuk manage questions per section

    public function manageSectionQuestions($questionnaire_id, $page_id, $section_id)
    {
        $questionModel = new QuestionModel();
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();
        $optionModel = new QuestionOptionModel();
        $matrixRowModel = new MatrixRowModel();

        // Ambil data detail
        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $page = $pageModel->find($page_id);
        $section = $sectionModel->find($section_id);

       

        if (!$questionnaire || !$page || !$section) {
            return redirect()->to('admin/questionnaire')
                ->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua pertanyaan di section ini
        $questions = $questionModel
            ->where('questionnaires_id', $questionnaire_id)
            ->where('page_id', $page_id)
            ->where('section_id', $section_id)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        // Hitung order_no berikutnya
        $next_order = count($questions) + 1;

        // Ambil semua pertanyaan untuk conditional logic (parent)
        $all_questions = $questionModel
            ->where('questionnaires_id', $questionnaire_id)
            ->where('page_id', $page_id)
            ->where('section_id', $section_id)
            ->orderBy('order_no', 'ASC')
            ->findAll();

        // Jenis pertanyaan (lebih lengkap dari enum lama)
        $question_types = [
            'text'      => 'Text Pendek',
            'textarea'  => 'Text Panjang',
            'radio'     => 'Pilihan Tunggal',
            'checkbox'  => 'Pilihan Ganda',
            'dropdown'  => 'Dropdown',
            'number'    => 'Angka',
            'date'      => 'Tanggal',
            'email'     => 'Email',
            'file'      => 'Upload File',
            'rating'    => 'Rating',
            'matrix'    => 'Matriks',
        ];


            foreach ($questions as $key => $q) {
            // Ambil opsi untuk radio, checkbox, dropdown
            if (in_array($q['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $questions[$key]['options'] = $optionModel->where('question_id', $q['id'])->orderBy('order_number', 'ASC')->findAll();
            } else {
                $questions[$key]['options'] = [];
            }

            // Ambil data matrix langsung dari JSON di questions
            if ($q['question_type'] === 'matrix') {
                $rows = new MatrixRowModel();
                $rows = $rows->where('question_id', $q['id'])->orderBy('order_no')->get()->getResultArray();
                $columns = new MatrixColumnModels();
                $columns = $columns->where('question_id', $q['id'])->orderBy('order_no')->get()->getResultArray();

                $questions[$key]['matrix_rows'] = $rows;
                $questions[$key]['matrix_columns'] = $columns;
                $questions[$key]['matrix_options'] = []; // kalau kamu punya opsi tambahan
            }



            if ($q === 'scale') {
                $data['scale_min'] = (int)($this->request->getPost('scale_min') ?? 1);
                $data['scale_max'] = (int)($this->request->getPost('scale_max') ?? 5);
                $data['scale_step'] = (int)($this->request->getPost('scale_step') ?? 1);
                // Batasi nilai maksimum dan minimum
                $data['scale_min'] = max(1, min(10, $data['scale_min'])); // Batasi min 1-10
                $data['scale_max'] = max(2, min(100, $data['scale_max'])); // Batasi max 2-100
                $data['scale_step'] = max(1, min(10, $data['scale_step'])); // Batasi step 1-10
                $data['scale_min_label'] = $this->request->getPost('scale_min_label');
                $data['scale_max_label'] = $this->request->getPost('scale_max_label');
                log_message('debug', 'Scale settings saved: min=' . $data['scale_min'] . ', max=' . $data['scale_max']);
            }
        }

        return view('adminpage/questioner/question/index', [
            'questionnaire'    => $questionnaire,
            'page'             => $page,
            'section'          => $section,
            'questions'        => $questions,
            'questionnaire_id' => $questionnaire_id,
            'page_id'          => $page_id,
            'section_id'       => $section_id,
            'question_types'   => $question_types,
            'next_order'       => $next_order,
            'all_questions'    => $all_questions
        ]);
    }
    
    public function getQuestionOptions($questionnaire_id, $page_id, $section_id, $questionId)
    {
        try {
            log_message('debug', "Mengambil opsi untuk questionId: $questionId, questionnaire: $questionnaire_id, page: $page_id, section: $section_id");
            
            $questionModel = new QuestionModel();
            $optionModel = new QuestionOptionModel();
            log_message('debug', 'Models initialized successfully');

            $question = $questionModel->where('id', $questionId)
                                    ->where('questionnaires_id', $questionnaire_id)
                                    ->where('page_id', $page_id)
                                    ->where('section_id', $section_id)
                                    ->first();
            log_message('debug', 'Question query executed: ' . ($question ? json_encode($question) : 'No question found'));

            if (!$question) {
                log_message('error', "Question not found for ID: $questionId");
                return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
            }

            $options = [];
            if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $options = $optionModel->where('question_id', $questionId)
                                    ->select('option_text, option_value, order_number') // Gunakan order_number
                                    ->orderBy('order_number', 'ASC') // Konsisten dengan order_number
                                    ->findAll();
                log_message('debug', "Options fetched: " . json_encode($options));
            } else {
                log_message('debug', "Question type {$question['question_type']} does not support options");
            }

            return $this->response->setJSON([
                'status' => 'success',
                'question_type' => $question['question_type'],
                'options' => $options
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getQuestionOptions: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ]);
        }
    }

    public function getOptions($questionId)
{
    $questionModel = new QuestionModel();
    $optionModel = new QuestionOptionModel();
    $question = $questionModel->find($questionId);

    if ($question) {
        $options = [];
        if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
            $options = $optionModel->where('question_id', $questionId)
                                ->orderBy('order_no', 'ASC')
                                ->findAll();
        }
        return $this->response->setJSON([
            'status' => 'success',
            'question_type' => $question['question_type'],
            'options' => $options
        ]);
    }

    return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
}

  public function storeSectionQuestion($questionnaire_id, $page_id, $section_id)
{
    log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));

    $validation = \Config\Services::validation();
    $validation->setRules([
        'question_text' => 'required',
        'question_type' => 'required|in_list[text,textarea,email,number,phone,radio,checkbox,dropdown,date,time,datetime,scale,matrix,file,user_field]',
        'is_required' => 'permit_empty|in_list[0,1]',
        'order_no' => 'required|integer',
        'options' => 'permit_empty',
        'scale_min' => 'permit_empty|integer|greater_than[0]|less_than[11]',
        'scale_max' => 'permit_empty|integer|greater_than[1]|less_than[101]',
        'scale_step' => 'permit_empty|integer|greater_than[0]|less_than[11]',
        'scale_min_label' => 'permit_empty',
        'scale_max_label' => 'permit_empty',
        'allowed_types' => 'permit_empty',
        'max_file_size' => 'permit_empty|integer',
        'matrix_rows' => 'permit_empty',
        'matrix_columns' => 'permit_empty',
        'enable_conditional' => 'permit_empty|in_list[0,1]',
        'parent_question_id' => 'permit_empty|integer',
        'condition_operator' => 'permit_empty|in_list[is,is not]',
        'condition_value' => 'permit_empty',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Validation errors: ' . print_r($validation->getErrors(), true));
        return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
    }
    
    $questionModel = new QuestionModel();
    $maxOrder = $questionModel->where([
        'questionnaires_id' => $questionnaire_id,
        'page_id' => $page_id,
        'section_id' => $section_id
    ])->selectMax('order_no')->first()['order_no'] ?? 0;

    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();
        $matrixRowModel = new \App\Models\MatrixRowModel();
        $matrixColumnModel = new \App\Models\MatrixColumnModels();

        $data = [
            'questionnaires_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_id' => $section_id,
            'question_text' => $this->request->getPost('question_text'),
            'question_type' => $this->request->getPost('question_type'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
           'order_no' => $maxOrder + 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Conditional logic to condition_json
        $enableConditional = $this->request->getPost('enable_conditional') ? 1 : 0;
        $parentId = $this->request->getPost('parent_question_id');
        if ($enableConditional && $parentId) {
            $condition = [
                'field' => 'question_' . $parentId,
                'operator' => $this->request->getPost('condition_operator') ?: 'is',
                'value' => $this->request->getPost('condition_value')
            ];
            $data['condition_json'] = json_encode([$condition]);
            log_message('debug', 'Saving condition_json: ' . $data['condition_json']);
        } else {
            $data['condition_json'] = null;
        }

        // Special type handling
        $type = $data['question_type'];
        log_message('debug', 'Processing special type: ' . $type);

        if ($type === 'scale') {
            $data['scale_min'] = (int)($this->request->getPost('scale_min') ?? 1);
            $data['scale_max'] = (int)($this->request->getPost('scale_max') ?? 5);
            $data['scale_step'] = max(1, (int)($this->request->getPost('scale_step') ?? 1));
            $data['scale_min_label'] = $this->request->getPost('scale_min_label');
            $data['scale_max_label'] = $this->request->getPost('scale_max_label');
            log_message('debug', 'Scale settings saved: min=' . $data['scale_min'] . ', max=' . $data['scale_max'] . ', step=' . $data['scale_step']);
        } elseif ($type === 'file') {
            $data['allowed_types'] = $this->request->getPost('allowed_types') ?? 'pdf,doc,docx';
            $data['max_file_size'] = $this->request->getPost('max_file_size') ?? 5;
            log_message('debug', 'File settings saved: types=' . $data['allowed_types'] . ', size=' . $data['max_file_size']);
        }

        // Insert question
        $question_id = $questionModel->insert($data);
        log_message('debug', 'Question ID inserted: ' . $question_id);

        // Matrix handling
        if ($type === 'matrix') {
            $rows = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_rows') ?? '')));
            $columns = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_columns') ?? '')));

            log_message('debug', 'Matrix rows: ' . print_r($rows, true));
            log_message('debug', 'Matrix columns: ' . print_r($columns, true));

            // Insert rows
            $rowOrder = 1;
            foreach ($rows as $row) {
                $matrixRowModel->insert([
                    'question_id' => $question_id,
                    'row_text' => $row,
                    'order_no' => $rowOrder++
                ]);
            }

            // Insert columns
            $colOrder = 1;
            foreach ($columns as $col) {
                $matrixColumnModel->insert([
                    'question_id' => $question_id,
                    'column_text' => $col,
                    'order_no' => $colOrder++
                ]);
            }
        }

        // Options (radio, checkbox, dropdown)
        if (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
            $options = $this->request->getPost('options');
            $optionValues = $this->request->getPost('option_values');
            $nextQuestionIds = $this->request->getPost('next_question_ids');

            if (!empty($options)) {
                $optionsToInsert = [];
                $order = 1;
                foreach ($options as $index => $opt) {
                    $optText = trim($opt);
                    if (!empty($optText)) {
                        $optValue = isset($optionValues[$index]) && !empty(trim($optionValues[$index]))
                            ? trim($optionValues[$index])
                            : strtolower(str_replace(' ', '_', $optText));
                        $nextQuestionId = $nextQuestionIds[$index] ?? null;
                        $optionsToInsert[] = [
                            'question_id' => $question_id,
                            'option_text' => $optText,
                            'option_value' => $optValue,
                            'next_question_id' => $nextQuestionId,
                            'order_number' => $order++
                        ];
                    }
                }
                if (!empty($optionsToInsert)) {
                    $optionModel->insertBatch($optionsToInsert);
                }
            }
        }

        if ($db->transStatus() === false) {
            log_message('error', 'Transaction failed before completion');
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed.']);
        }

        $db->transComplete();
        log_message('debug', 'Transaction completed successfully');

        return $this->response->setJSON(['status' => 'success', 'message' => 'Pertanyaan berhasil ditambahkan.']);
    } catch (\Exception $e) {
        log_message('error', 'Exception in storeSectionQuestion: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());

        $db->transRollback();
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan pertanyaan: ' . $e->getMessage()]);
    }
}


// edit method

public function updateQuestion($questionnaire_id, $page_id, $section_id, $question_id)
{
    $validation = \Config\Services::validation();
    $validation->setRules([
        'question_id' => 'required|integer',
        'question_text' => 'required',
        'question_type' => 'required|in_list[text,textarea,email,number,phone,radio,checkbox,dropdown,date,time,datetime,scale,matrix,file,user_field]',
        'is_required' => 'permit_empty|in_list[0,1]',
        'order_no' => 'permit_empty|integer',
        'options' => 'permit_empty',
        'scale_min' => 'permit_empty|integer|greater_than[0]|less_than[11]',
        'scale_max' => 'permit_empty|integer|greater_than[1]|less_than[101]',
        'scale_step' => 'permit_empty|integer|greater_than[0]|less_than[11]',
        'scale_min_label' => 'permit_empty',
        'scale_max_label' => 'permit_empty',
        'allowed_types' => 'permit_empty',
        'max_file_size' => 'permit_empty|integer',
        'matrix_rows' => 'permit_empty',
        'matrix_columns' => 'permit_empty',
        'enable_conditional' => 'permit_empty|in_list[0,1]',
        'parent_question_id' => 'permit_empty|integer',
        'condition_operator' => 'permit_empty|in_list[is,is not]',
        'condition_value' => 'permit_empty',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Validation errors: ' . print_r($validation->getErrors(), true));
        return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();
        $rowModel = new \App\Models\MatrixRowModel();
        $colModel = new \App\Models\MatrixColumnModels();

        $question = $questionModel->find($question_id);
        if (!$question) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
        }

        $data = [
            'question_text' => $this->request->getPost('question_text'),
            'question_type' => $this->request->getPost('question_type'),
            'is_required' => $this->request->getPost('is_required') ? 1 : 0,
            'order_no' => $this->request->getPost('order_no') ?? $question['order_no'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Conditional logic
        $enableConditional = $this->request->getPost('enable_conditional') ? 1 : 0;
        $parentId = $this->request->getPost('parent_question_id');
        if ($enableConditional && $parentId) {
            $condition = [
                'field' => 'question_' . $parentId,
                'operator' => $this->request->getPost('condition_operator') ?: 'is',
                'value' => $this->request->getPost('condition_value')
            ];
            $data['condition_json'] = json_encode([$condition]);
            log_message('debug', 'Saving condition_json: ' . $data['condition_json']);
        } else {
            $data['condition_json'] = null;
            log_message('debug', 'No conditional logic enabled, condition_json set to null');
        }

        // Special type handling
        $type = $data['question_type'];
        if ($type === 'scale') {
            $data['scale_min'] = (int)($this->request->getPost('scale_min') ?? 1);
            $data['scale_max'] = (int)($this->request->getPost('scale_max') ?? 5);
            $data['scale_step'] = max(1, (int)($this->request->getPost('scale_step') ?? 1));
            $data['scale_min_label'] = $this->request->getPost('scale_min_label');
            $data['scale_max_label'] = $this->request->getPost('scale_max_label');
        } elseif ($type === 'file') {
            $data['allowed_types'] = $this->request->getPost('allowed_types') ?? 'pdf,doc,docx';
            $data['max_file_size'] = $this->request->getPost('max_file_size') ?? 5;
        }

        $questionModel->update($question_id, $data);

        // Matrix handling (rows & columns simpan ke tabel terpisah)
        if ($type === 'matrix') {
            $rows = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_rows') ?? '')));
            $columns = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_columns') ?? '')));

            $rowModel->where('question_id', $question_id)->delete();
            $colModel->where('question_id', $question_id)->delete();

            log_message('debug', 'Matrix rows: ' . print_r($rows, true));
            log_message('debug', 'Matrix columns: ' . print_r($columns, true));

            // insert rows
            $rowOrder = 1;
            foreach ($rows as $row) {
                $rowModel->insert([
                    'question_id' => $question_id,
                    'row_text' => $row,
                    'order_no' => $rowOrder++
                ]);
            }

            // insert columns
            $colOrder = 1;
            foreach ($columns as $col) {
                $colModel->insert([
                    'question_id' => $question_id,
                    'column_text' => $col,
                    'order_no' => $colOrder++
                ]);
            }
        }

        // Options (radio, checkbox, dropdown)
        if (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
            $optionModel->where('question_id', $question_id)->delete();
            $options = $this->request->getPost('options');
            $optionValues = $this->request->getPost('option_values');
            $nextQuestionIds = $this->request->getPost('next_question_ids');

            if (!empty($options)) {
                $optionsToInsert = [];
                $order = 1;
                foreach ($options as $index => $opt) {
                    $optText = trim($opt);
                    if (!empty($optText)) {
                        $optValue = isset($optionValues[$index]) && !empty(trim($optionValues[$index]))
                            ? trim($optionValues[$index])
                            : strtolower(str_replace(' ', '_', $optText));
                        $nextQuestionId = $nextQuestionIds[$index] ?? null;
                        $optionsToInsert[] = [
                            'question_id' => $question_id,
                            'option_text' => $optText,
                            'option_value' => $optValue,
                            'next_question_id' => $nextQuestionId,
                            'order_number' => $order++
                        ];
                    }
                }
                if (!empty($optionsToInsert)) {
                    $optionModel->insertBatch($optionsToInsert);
                }
            }
        }

        if ($db->transStatus() === false) {
            log_message('error', 'Transaction failed before completion');
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed.']);
        }

        $db->transComplete();
        log_message('debug', 'Transaction completed successfully');

        return $this->response->setJSON(['status' => 'success', 'message' => 'Pertanyaan berhasil diupdate.']);
    } catch (\Exception $e) {
        log_message('error', 'Exception in updateQuestion: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());

        $db->transRollback();
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal mengupdate pertanyaan: ' . $e->getMessage()]);
    }
}

    public function getQuestionsWithOptions($questionnaire_id, $page_id, $section_id)
    {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();
        
        // Get all questions in this section that could be parent questions
        $questions = $questionModel
            ->where('questionnaires_id', $questionnaire_id)
            ->where('page_id', $page_id) 
            ->where('section_id', $section_id)
            ->where('question_type IN', ['radio', 'checkbox', 'dropdown']) // Only questions with options
            ->orderBy('order_no', 'ASC')
            ->findAll();
        
        $questionsWithOptions = [];
        
        foreach ($questions as $question) {
            $options = $optionModel->where('question_id', $question['id'])
                                ->orderBy('order_no', 'ASC') 
                                ->findAll();
            
            $questionsWithOptions[] = [
                'question' => $question,
                'options' => $options
            ];
        }
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $questionsWithOptions
            ]);
        }
        
        return $questionsWithOptions;
    }
   public function deleteSectionQuestion($questionnaire_id, $page_id, $section_id, $question_id)
    {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();
        $rowModel = new \App\Models\MatrixRowModel();
        $colModel = new \App\Models\MatrixColumnModels();

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // hapus opsi biasa
            $optionModel->where('question_id', $question_id)->delete();

            // hapus rows & columns matrix
            $rowModel->where('question_id', $question_id)->delete();
            $colModel->where('question_id', $question_id)->delete();

            // hapus pertanyaan utama
            $deleted = $questionModel->where('id', $question_id)->delete();

            $db->transComplete();

            if ($deleted) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Pertanyaan berhasil dihapus.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus pertanyaan.']);
            }
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    public function getQuestion($questionnaire_id, $page_id, $section_id, $question_id)
{
    log_message('debug', "Fetching question: ID=$question_id, questionnaire=$questionnaire_id, page=$page_id, section=$section_id");
    $questionModel = new QuestionModel();
    $optionModel = new QuestionOptionModel();
    $rowModel = new \App\Models\MatrixRowModel();
    $colModel = new \App\Models\MatrixColumnModels();

    $question = $questionModel
        ->where('id', $question_id)
        ->where('questionnaires_id', $questionnaire_id)
        ->where('page_id', $page_id)
        ->where('section_id', $section_id)
        ->first();

    if (!$question) {
        log_message('error', "Question not found for ID: $question_id");
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Question not found'
        ]);
    }

    // Tambah opsi untuk radio/checkbox/dropdown
    if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
        $question['options'] = $optionModel->where('question_id', $question_id)
            ->orderBy('order_number', 'ASC')
            ->findAll();
    } else {
        $question['options'] = [];
    }

    // Tambah rows & columns untuk matrix
    if ($question['question_type'] === 'matrix') {
        $rows = $rowModel->where('question_id', $question_id)->orderBy('order_no', 'ASC')->findAll();
        $cols = $colModel->where('question_id', $question_id)->orderBy('order_no', 'ASC')->findAll();
        $question['matrix_rows'] = array_column($rows, 'row_text');
        $question['matrix_columns'] = array_column($cols, 'column_text');
        $question['matrix_options'] = [];
    }

    log_message('debug', 'Question fetched: ' . json_encode($question));
    return $this->response->setJSON([
        'status' => 'success',
        'question' => $question
    ]);
}


}