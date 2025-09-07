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
            'email',
            'username',
            'group_id',
            'display_name',
            'academic_nim',
            'jurusan',
            'id_prodi',
            'academic_year',
            'academic_ipk',
            'street_1',
            'street_2',
            'city',
            'state_code',
            'academic_graduate_year',
            'jenis_kel',
            'HP'
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
            case 'id_jurusan':
                $facultyModel = new Jurusan();
                $options = $facultyModel->select('id, nama_jurusan as name')->findAll();
                $type = 'select';
                break;
            case 'id_prodi':
                $programModel = new Prodi();
                $options = $programModel->select('id, nama_prodi as name')->findAll();
                $type = 'select';
                break;
            case 'jenisKelamin':
                $options = [['id' => 'L', 'name' => 'Laki-laki'], ['id' => 'P', 'name' => 'Perempuan']];
                $type = 'select';
                break;
            case 'tahun_kelulusan':
            case 'tahun_kelulusan':
                $options = [];
                for ($i = date('Y'); $i >= 2000; $i--) {
                    $options[] = ['id' => (string)$i, 'name' => (string)$i];
                }
                $type = 'select';
                break;
            case 'id_cities':
                $cityModel = new Provincies();
                $options = $cityModel->select('id, name')->findAll();
                $type = 'select';
                break;
            case 'role':
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
            'email',
            'username',
            'group_id',
            'display_name',
            'academic_nim',
            'academic_faculty',
            'academic_program',
            'academic_year',
            'academic_ipk',
            'street_1',
            'street_2',
            'city',
            'state_code',
            'academic_graduate_year',
            'jenis_kel',
            'HP'
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
            'status' => $this->request->getPost('status'),
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
            if (in_array($q['question_type'], ['radio', 'checkbox', 'dropdown'])) {
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
                $questions[$key]['matrix_rows'] = json_decode($q['matrix_rows'] ?? '[]', true);
                $questions[$key]['matrix_columns'] = json_decode($q['matrix_columns'] ?? '[]', true);
                $questions[$key]['matrix_options'] = json_decode($q['matrix_options'] ?? '[]', true);
            } else {
                $questions[$key]['matrix_rows'] = [];
                $questions[$key]['matrix_columns'] = [];
                $questions[$key]['matrix_options'] = [];
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

    public function getQuestionOptions($question_id)
    {
        $optionModel = new QuestionOptionModel();
        $options = $optionModel->where('question_id', $question_id)
            ->orderBy('order_number', 'ASC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'options' => $options
        ]);
    }
    public function getOptions($questionId)
    {
        $questionModel = new QuestionModel();
        $question = $questionModel->with('options')->find($questionId);

        if ($question) {
            return $this->response->setJSON([
                'status' => 'success',
                'options' => $question['options'] ?? []
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
    }

    public function storeSectionQuestion($questionnaire_id, $page_id, $section_id)
    {
        // ===== STEP 1: DEBUG POST DATA =====
        log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));

        $validation = \Config\Services::validation();
        $validation->setRules([
            'question_title' => 'required|max_length[255]',
            'question_text' => 'required',
            'question_type' => 'required|in_list[text,textarea,email,number,phone,radio,checkbox,dropdown,date,time,datetime,scale,matrix,file,user_field]',
            'is_required' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer',
            'options' => 'permit_empty',
            'scale_min' => 'permit_empty|integer',
            'scale_max' => 'permit_empty|integer',
            'scale_step' => 'permit_empty|integer',
            'scale_min_label' => 'permit_empty',
            'scale_max_label' => 'permit_empty',
            'allowed_types' => 'permit_empty',
            'max_file_size' => 'permit_empty|integer',
            'matrix_rows' => 'permit_empty',
            'matrix_columns' => 'permit_empty',
            'parent_question_id' => 'permit_empty|integer',
            'condition_operator' => 'permit_empty|in_list[is,is not]',
            'condition_value' => 'permit_empty',
            'scale_min' => 'permit_empty|integer|greater_than[0]|less_than[11]',
            'scale_max' => 'permit_empty|integer|greater_than[1]|less_than[101]',
            'scale_step' => 'permit_empty|integer|greater_than[0]|less_than[11]',
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

            $data = [
                'questionnaires_id' => $questionnaire_id,
                'page_id' => $page_id,
                'section_id' => $section_id,
                'question_title' => $this->request->getPost('question_title'),
                'question_text' => $this->request->getPost('question_text'),
                'question_type' => $this->request->getPost('question_type'),
                'is_required' => $this->request->getPost('is_required') ? 1 : 0,
                'order_no' => $this->request->getPost('order_no'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Handle conditional logic as JSON
            $parentId = $this->request->getPost('parent_question_id');
            if ($parentId) {
                $condition = [
                    'field' => 'question_' . $parentId,
                    'operator' => $this->request->getPost('condition_operator') ?: 'is',
                    'value' => $this->request->getPost('condition_value')
                ];
                $data['condition_json'] = json_encode([$condition]);
            }

            // Handle special types
            $type = $data['question_type'];
            log_message('debug', 'Processing special type: ' . $type);
            if ($type === 'scale') {
                $data['scale_min'] = (int)($this->request->getPost('scale_min') ?? 1);
                $data['scale_max'] = (int)($this->request->getPost('scale_max') ?? 5);
                $data['scale_step'] = (int)($this->request->getPost('scale_step') ?? 1);
                // Pastikan step tidak nol
                $data['scale_step'] = max(1, $data['scale_step']);
                $data['scale_min_label'] = $this->request->getPost('scale_min_label');
                $data['scale_max_label'] = $this->request->getPost('scale_max_label');
                log_message('debug', 'Scale settings saved: min=' . $data['scale_min'] . ', max=' . $data['scale_max'] . ', step=' . $data['scale_step']);
            } elseif ($type === 'file') {
                $data['allowed_types'] = $this->request->getPost('allowed_types') ?? 'pdf,doc,docx';
                $data['max_file_size'] = $this->request->getPost('max_file_size') ?? 5;
                log_message('debug', 'File settings saved: types=' . $data['allowed_types'] . ', size=' . $data['max_file_size']);
            } elseif ($type === 'matrix') {
                $rows = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_rows') ?? '')));
                $columns = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_columns') ?? '')));
                $data['matrix_rows'] = json_encode($rows);
                $data['matrix_columns'] = json_encode($columns);
                log_message('debug', 'Matrix settings saved: rows=' . $data['matrix_rows'] . ', columns=' . $data['matrix_columns']);
            }

            // Insert question
            $question_id = $questionModel->insert($data);
            log_message('debug', 'Question ID inserted: ' . $question_id);

            // Handle options for selection types
            if (in_array($type, ['radio', 'checkbox', 'dropdown'])) {
                log_message('debug', 'Processing options for question type: ' . $type);

                $options = $this->request->getPost('options');
                $optionValues = $this->request->getPost('option_values');
                $nextQuestionIds = $this->request->getPost('next_question_ids');
                log_message('debug', 'Options from POST: ' . print_r($options, true));

                if (!empty($options)) {
                    $optionsToInsert = [];
                    $order = 1;
                    foreach ($options as $index => $opt) {
                        $optText = trim($opt);
                        log_message('debug', "Processing option: '{$optText}'");

                        if (!empty($optText)) {
                            $optValue = isset($optionValues[$index]) && !empty(trim($optionValues[$index]))
                                ? trim($optionValues[$index])
                                : strtolower(str_replace(' ', '_', $optText));
                            $nextQuestionId = isset($nextQuestionIds[$index]) && !empty($nextQuestionIds[$index])
                                ? $nextQuestionIds[$index]
                                : null;
                            $optionsToInsert[] = [
                                'question_id' => $question_id,
                                'option_text' => $optText,
                                'option_value' => $optValue,
                                'next_question_id' => $nextQuestionId,
                                'order_no' => $order++
                            ];
                        }
                    }

                    log_message('debug', 'Total options to insert: ' . count($optionsToInsert));

                    if (!empty($optionsToInsert)) {
                        $result = $optionModel->insertBatch($optionsToInsert);
                        log_message('debug', 'Options insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                        if (!$result) {
                            $errors = $optionModel->errors();
                            log_message('error', 'Option insert errors: ' . print_r($errors, true));
                        }
                    } else {
                        log_message('warning', 'No valid options to insert');
                    }
                } else {
                    log_message('warning', 'No options received from POST');
                }
            }

            // Check transaction status
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
    public function getQuestion($questionnaire_id, $page_id, $section_id, $question_id)
    {
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();

        $question = $questionModel->find($question_id);
        if ($question) {
            if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $question['options'] = $optionModel->where('question_id', $question_id)->orderBy('order_number', 'ASC')->findAll();
            } else {
                $question['options'] = [];
            }
            if ($question['question_type'] === 'matrix') {
                $question['matrix_rows'] = json_decode($question['matrix_rows'] ?? '[]', true);
                $question['matrix_columns'] = json_decode($question['matrix_columns'] ?? '[]', true);
                $question['matrix_options'] = json_decode($question['matrix_options'] ?? '[]', true);
            }
            return $this->response->setJSON(['status' => 'success', 'question' => $question]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
    }

    // edit method

    public function updateQuestion($questionnaire_id, $page_id, $section_id, $question_id)
    {

        log_message('debug', 'Received POST data: ' . json_encode($this->request->getPost()));

        $validation = \Config\Services::validation();
        $validation->setRules([
            'question_id' => 'required|integer',
            'question_title' => 'required|max_length[255]',
            'question_text' => 'required',
            'question_type' => 'required|in_list[text,textarea,email,number,phone,radio,checkbox,dropdown,date,time,datetime,scale,matrix,file,user_field]',
            'is_required' => 'permit_empty|in_list[0,1]',
            'order_no' => 'required|integer',
            'scale_min' => 'permit_empty|integer|greater_than[0]|less_than[11]',
            'scale_max' => 'permit_empty|integer|greater_than[1]|less_than[101]',
            'scale_step' => 'permit_empty|integer|greater_than[0]|less_than[11]',
            'allowed_types' => 'permit_empty',
            'max_file_size' => 'permit_empty|integer',
            'matrix_rows' => 'permit_empty',
            'matrix_columns' => 'permit_empty',
            'parent_question_id' => 'permit_empty|integer',
            'condition_operator' => 'permit_empty|in_list[is,is not]',
            'condition_value' => 'permit_empty',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            log_message('error', 'Validation failed: ' . json_encode($validation->getErrors()));
            return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $questionModel = new QuestionModel();
            $question = $questionModel->find($question_id);

            if (!$question) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
            }

            $data = [
                'question_title' => $this->request->getPost('question_title'),
                'question_text' => $this->request->getPost('question_text'),
                'question_type' => $this->request->getPost('question_type'),
                'is_required' => $this->request->getPost('is_required') ? 1 : 0,
                'order_no' => $this->request->getPost('order_no'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Handle conditional logic
            $parentId = $this->request->getPost('parent_question_id');
            if ($parentId) {
                $condition = [
                    'field' => 'question_' . $parentId,
                    'operator' => $this->request->getPost('condition_operator') ?: 'is',
                    'value' => $this->request->getPost('condition_value')
                ];
                $data['condition_json'] = json_encode([$condition]);
            } else {
                $data['condition_json'] = null;
            }

            // Handle special types
            $type = $data['question_type'];
            if ($type === 'scale') {
                $data['scale_min'] = (int)($this->request->getPost('scale_min') ?? 1);
                $data['scale_max'] = (int)($this->request->getPost('scale_max') ?? 5);
                $data['scale_step'] = (int)($this->request->getPost('scale_step') ?? 1);
                $data['scale_step'] = max(1, $data['scale_step']);
                $data['scale_min_label'] = $this->request->getPost('scale_min_label');
                $data['scale_max_label'] = $this->request->getPost('scale_max_label');
            } elseif ($type === 'file') {
                $data['allowed_types'] = $this->request->getPost('allowed_types') ?? 'pdf,doc,docx';
                $data['max_file_size'] = (int)($this->request->getPost('max_file_size') ?? 5);
            } elseif ($type === 'matrix') {
                $rows = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_rows') ?? '')));
                $columns = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_columns') ?? '')));
                $options = array_filter(array_map('trim', explode(',', $this->request->getPost('matrix_options') ?? '')));
                $data['matrix_rows'] = json_encode($rows);
                $data['matrix_columns'] = json_encode($columns);
                $data['matrix_options'] = json_encode($options);
            }

            $questionModel->update($question_id, $data);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
            }

            $db->transComplete();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Question updated successfully']);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Update failed: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update question: ' . $e->getMessage()]);
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

        log_message('debug', "Deleting question ID: $question_id"); // Debug log
        // Hapus opsi terkait
        $optionModel->where('question_id', $question_id)->delete();

        // Hapus pertanyaan
        $deleted = $questionModel->where('id', $question_id)->delete();

        if ($deleted) {
            log_message('debug', "Question ID $question_id deleted successfully");
            return $this->response->setJSON(['status' => 'success', 'message' => 'Pertanyaan berhasil dihapus.']);
        } else {
            log_message('error', "Failed to delete question ID: $question_id");
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus pertanyaan.']);
        }
    }
}
