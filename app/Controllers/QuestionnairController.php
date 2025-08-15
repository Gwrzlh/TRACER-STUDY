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
    public function storeInlineQuestion($questionnaire_id, $page_id, $section_id)
    {
        $questionModel = new QuestionModel();
        $questionnaireModel = new QuestionnairModel();
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();

        $questionnaire = $questionnaireModel->find($questionnaire_id);
        $section = $sectionModel->find($section_id);
        $page = $pageModel->find($page_id);

        // dd($section,$section_id);

        $data = [
            'questionnaires_id' => $questionnaire['id'],
            'page_id'           => $page['id'],
            'section_id'        => $section['id'],
            'question_text'     => $this->request->getPost('question_text'),
            'question_type'     => $this->request->getPost('question_type'),
            'is_required'       => $this->request->getPost('is_required') ? 1 : 0,
            'order_no'          => $this->request->getPost('order_no') ?? 1,
            'parent_question_id'=> $this->request->getPost('parent_question_id') ?: null,
            'condition_value'   => $this->request->getPost('condition_value'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if (!$data['question_text'] || !$data['question_type']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pertanyaan dan jenis pertanyaan wajib diisi!'
            ]);
        }

        $insertId = $questionModel->insert($data);

        // Ambil data lengkap buat dikirim balik
        $newQuestion = $questionModel->find($insertId);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Pertanyaan berhasil ditambahkan',
            'data' => $newQuestion
        ]);
    }


   public function createSectionQuestion($questionnaire_id, $page_id, $section_id)
    {
        $questionModel = new QuestionModel();
        $sectionModel = new SectionModel();
        $pageModel = new QuestionnairePageModel();
        $questionnaireModel = new QuestionnairModel();

        $section = $sectionModel->find($section_id);
        $page = $pageModel->find($page_id);
        $questionnaire = $questionnaireModel->find($questionnaire_id);

        if (!$section || !$page || !$questionnaire) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

          $lastQuestion = $questionModel
        ->where('questionnaires_id', $questionnaire_id)
        ->where('page_id', $page_id)
        ->where('section_id', $section_id)
        ->orderBy('order_no', 'DESC')
        ->first();

        $next_order = $lastQuestion ? ($lastQuestion['order_no'] + 1) : 1;

         $all_questions = $questionModel
        ->where('questionnaires_id', $questionnaire_id)
        ->where('page_id', $page_id)
        ->where('section_id', $section_id)
        ->orderBy('order_no', 'ASC')
        ->findAll();


        // Daftar jenis pertanyaan
        $question_types = [
            'text' => 'Text Pendek',
            'textarea' => 'Text Panjang',
            'radio' => 'Pilihan Tunggal',
            'checkbox' => 'Pilihan Ganda',
            'dropdown' => 'Dropdown',
            'number' => 'Angka',
            'date' => 'Tanggal',
            'email' => 'Email',
            'file' => 'Upload File',
            'rating' => 'Rating',
            'matrix' => 'Matriks',
        ];

        return view('adminpage/questioner/question/create', [
            'questionnaire_id' => $questionnaire_id,
            'page_id' => $page_id,
            'section_id' => $section_id,
            'questionnaire' => $questionnaire, // kirim data questionnaire
            'page' => $page,                   // kirim data page
            'section' => $section,             // kirim data section (supaya bisa $section['title'])
            'question_types' => $question_types,
            'next_order' => $next_order,
            'all_questions' => $all_questions
        ]);
    }

    public function getQuestionOptions($question_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        $optionModel = new QuestionOptionModel();
        $questionModel = new QuestionModel();
        
        // Get question details
        $question = $questionModel->find($question_id);
        if (!$question) {
            return $this->response->setJSON(['error' => 'Question not found']);
        }
        
        // Get options for this question
        $options = $optionModel->where('question_id', $question_id)
                            ->orderBy('order_number', 'ASC')
                            ->findAll();
        
        return $this->response->setJSON([
            'status' => 'success',
            'question' => $question,
            'options' => $options
        ]);
    }

   public function storeSectionQuestion($questionnaire_id, $page_id, $section_id)
    {
        // ===== STEP 1: DEBUG POST DATA =====
        log_message('debug', 'POST Data: ' . print_r($this->request->getPost(), true));
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'question_text' => 'required|min_length[3]',
            'question_type' => 'required|in_list[text,textarea,radio,checkbox,dropdown,number,date,email,file,rating,matrix]',
            'order_no' => 'required|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
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
                'question_text' => $this->request->getPost('question_text'),
                'question_type' => $this->request->getPost('question_type'),
                'is_required' => $this->request->getPost('is_required') ?? 0,
                'order_no' => $this->request->getPost('order_no'),
                'parent_question_id' => $this->request->getPost('parent_question_id') ?: null,
                'condition_value' => $this->request->getPost('condition_value') ?: null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // ===== STEP 2: DEBUG QUESTION INSERT =====
            $question_id = $questionModel->insert($data);
            log_message('debug', 'Question ID inserted: ' . $question_id);
            log_message('debug', 'Question Type: ' . $data['question_type']);

            // ===== STEP 3: DEBUG OPTIONS HANDLING =====
            if (in_array($data['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                log_message('debug', 'Processing options for question type: ' . $data['question_type']);
                
                $options = $this->request->getPost('options');
                log_message('debug', 'Options from POST: ' . print_r($options, true));
                
                if (!empty($options)) {
                    $optionsToInsert = [];
                    $order = 1;
                    foreach ($options as $opt) {
                        $optText = trim($opt);
                        log_message('debug', "Processing option: '{$optText}'");
                        
                        if (!empty($optText)) {
                            $optionData = [
                                'question_id' => $question_id,
                                'option_text' => $optText,
                                'option_value' => strtolower(str_replace(' ', '_', $optText)),
                                'order_number' => $order++
                            ];
                            $optionsToInsert[] = $optionData;
                            log_message('debug', 'Option to insert: ' . print_r($optionData, true));
                        }
                    }
                    
                    log_message('debug', 'Total options to insert: ' . count($optionsToInsert));
                    
                    if (!empty($optionsToInsert)) {
                        // ===== STEP 4: TRY INDIVIDUAL INSERT INSTEAD OF BATCH =====
                        foreach ($optionsToInsert as $optionData) {
                            $result = $optionModel->insert($optionData);
                            log_message('debug', 'Option insert result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                            if (!$result) {
                                $errors = $optionModel->errors();
                                log_message('error', 'Option insert errors: ' . print_r($errors, true));
                            }
                        }
                    } else {
                        log_message('warning', 'No valid options to insert');
                    }
                } else {
                    log_message('warning', 'No options received from POST');
                }
            } else {
                log_message('debug', 'Question type does not require options: ' . $data['question_type']);
            }

            // ===== STEP 5: CHECK TRANSACTION STATUS =====
            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed before completion');
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Transaction failed.');
            }

            $db->transComplete();
            log_message('debug', 'Transaction completed successfully');

            return redirect()->to("admin/questionnaire/{$questionnaire_id}/pages/{$page_id}/sections/{$section_id}/questions")
                ->with('success', 'Pertanyaan berhasil ditambahkan.');

        } catch (\Exception $e) {
            log_message('error', 'Exception in storeSectionQuestion: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage());
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
                                ->orderBy('order_number', 'ASC') 
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

        $optionModel->where('question_id', $question_id)->delete();
        $questionModel->delete($question_id);

        return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus.');
    }
    
    

}