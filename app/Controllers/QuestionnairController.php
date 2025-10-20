<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuestionModel;
// use App\Models\QuestionOptionModel;
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
use App\Models\AnswerModel;
use App\Models\ResponseModel;
use App\Models\QuestionOptionModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use Exception;

class QuestionnairController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;
    protected $questionModel;
    protected $questionOptionModel;
    protected $matrixColumnModel;
    protected $matrixRowModel;
    protected $sectionModel; 
    protected $questionnairePageModel;  

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel = new AnswerModel();
        $this->questionModel = new QuestionModel();
        $this->questionOptionModel = new QuestionOptionModel();
        $this->matrixColumnModel = new MatrixColumnModels();
        $this->matrixRowModel = new MatrixRowModel();
        $this->sectionModel = new SectionModel();
        $this->questionnairePageModel = new QuestionnairePageModel();
        helper('questionnaire_helper');

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        // Initialize logger
        $this->logger = \Config\Services::logger();
        // Asumsikan model lain seperti QuestionnairePageModel, dll., jika diperlukan
    }

    public function index()
    {
        $model = new QuestionnairModel();
        
        $model->orderBy('created_at', 'DESC');
        
        $data = [
            'questionnaires' => $model->paginate(10), // 10 data per halaman
            'pager' => $model->pager // Untuk render pagination di view
        ];
        return view('adminpage/questioner/index', $data);
    }

    public function create()
    {
        $operators = [
            'is' => 'Is',
            'is_not' => 'Is Not',
            // 'contains' => 'Contains',
            // 'not_contains' => 'Not Contains',
            // 'greater' => 'Greater Than',
            // 'less' => 'Less Than'
        ];

        $user_fields = [
        'email' => 'Email',
        'username' => 'Username',
        'role_id' => 'Role ID',
        'nama_lengkap' => 'Nama Lengkap',
        'nim' => 'NIM',
        'id_jurusan' => 'Jurusan',
        'id_prodi' => 'Prodi',
        'angkatan' => 'Tahun Masuk',  // Ini yang diubah: display 'Tahun Masuk', actual 'angkatan'
        'ipk' => 'IPK',
        'alamat' => 'Alamat',
        'alamat2' => 'Alamat 2',
        'id_provinsi' => 'Provinsi',
        'kodepos' => 'Kode Pos',
        'tahun_kelulusan' => 'Tahun lulus',
        'jenisKelamin' => 'Jenis Kelamin',
        'no_tlp' => 'No TLP'
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
                $options = [['id' => 'Laki-Laki', 'name' => 'Laki-Laki'], ['id' => 'Perempuan', 'name' => 'Perempuan']];
                $type = 'select';
                break;
            case 'tahun_kelulusan':
                $options = [];
                for ($i = date('Y'); $i >= 2000; $i--) {
                    $options[] = ['id' => (string)$i, 'name' => (string)$i];
                }
                $type = 'select';
                break;
            case 'id_provinsi':
                $cityModel = new Provincies();
                $options = $cityModel->select('id, name')->findAll();
                $type = 'select';
                break;
            case 'role_id':
                $groupModel = new Roles();
                $options = $groupModel->select('id, nama as name')->findAll();
                $type = 'select';
                break;
            case 'angkatan':
                $options = [];
                for ($i = date('Y'); $i >= 2000; $i--) {
                    $options[] = ['id' => (string)$i, 'name' => (string)$i];
                }
                $type = 'select';
                break;
        }

        return $this->response->setJSON([
            'type' => $type,
            'options' => $options
        ]);
    }

    // Simpan kuesioner baru
    public function store()
    {
        $questionnaireModel = new QuestionnairModel();

        $title       = $this->request->getPost('title');
        $description = $this->request->getPost('deskripsi');
        $is_active   = $this->request->getPost('is_active');
        $announcement = $this->request->getPost('announcement');
        $conditionalLogic = null;

        // Handle conditional logic
        if ($this->request->getPost('conditional_logic')) {
            $fields    = $this->request->getPost('field_name');
            $operators = $this->request->getPost('operator');
            $values    = $this->request->getPost('value');

            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[$i]) && !empty($operators[$i]) && !empty($values[$i])) {
                    $conditions[] = [
                        'field'    => $fields[$i],
                        'operator' => $operators[$i],
                        'value'    => $values[$i]
                    ];
                }
            }
            if (!empty($conditions)) {
                $conditionalLogic = json_encode($conditions);
            }
        }

        $questionnaireModel->insert([
            'title'             => $title,
            'deskripsi'         => $description,
            'is_active'         => $is_active, // enum langsung
            'conditional_logic' => $conditionalLogic,
            'announcement'      => $announcement,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
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
            // 'contains' => 'Contains',
            // 'not_contains' => 'Not Contains',
            // 'greater' => 'Greater Than',
            // 'less' => 'Less Than'
        ];

        $user_fields = [
        'email' => 'Email',
        'username' => 'Username',
        'role_id' => 'Role ID',
        'nama_lengkap' => 'Nama Lengkap',
        'nim' => 'NIM',
        'id_jurusan' => 'Jurusan',
        'id_prodi' => 'Prodi',
        'angkatan' => 'Tahun Masuk',
        'ipk' => 'IPK',
        'alamat' => 'Alamat',
        'alamat2' => 'Alamat 2',
        'id_provinsi' => 'Provinsi',
        'kodepos' => 'Kode Pos',
        'tahun_kelulusan' => 'Tahun lulus',
        'jenisKelamin' => 'Jenis Kelamin',
        'no_tlp' => 'No TLP'
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


    public function update($questionnaire_id)
    {
        $model = new QuestionnairModel();
        $questionnaire = $model->find($questionnaire_id);

        if (!$questionnaire) {
            return redirect()->to('admin/questionnaire')->with('error', 'Data tidak ditemukan.');
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'title'      => 'required|min_length[3]|max_length[255]',
            'deskripsi'  => 'permit_empty|max_length[1000]',
            'is_active'  => 'required|in_list[active,draft,inactive]',
            'announcement' => 'required|max_length[1000]'
        ]);

            $announcement = $this->request->getPost('announcement');


        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $is_active = $this->request->getPost('is_active');
        $conditionalLogic = null;

        if ($this->request->getPost('conditional_logic')) {
            $fields    = $this->request->getPost('field_name');
            $operators = $this->request->getPost('operator');
            $values    = $this->request->getPost('value');

            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[$i]) && !empty($operators[$i]) && !empty($values[$i])) {
                    $conditions[] = [
                        'field'    => $fields[$i],
                        'operator' => $operators[$i],
                        'value'    => $values[$i]
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
            'announcement' => $announcement,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('admin/questionnaire')->with('success', 'Kuesioner berhasil diperbarui!');
    }




    public function delete($id)
    {
        $questionnaireModel = new QuestionnairModel();
        $pageModel          = new QuestionnairePageModel();
        $sectionModel       = new SectionModel();
        $questionModel      = new QuestionModel();
        $optionModel        = new QuestionOptionModel();
        $matrixRowModel     = new MatrixRowModel();
        $matrixColumnModel  = new MatrixColumnModels();
        $answerModel        = new AnswerModel();
        $responseModel      = new ResponseModel(); // Hapus responses terkait questionnaire

        // 1️⃣ Hapus semua responses terkait questionnaire
        $responseModel->where('questionnaire_id', $id)->delete();

        // 2️⃣ Ambil semua pertanyaan dari questionnaire ini
        $questions = $questionModel->where('questionnaires_id', $id)->findAll();

        foreach ($questions as $q) {
            // Hapus semua answers terkait pertanyaan
            $answerModel->where('question_id', $q['id'])->delete();

            // Hapus semua options terkait pertanyaan
            $optionModel->where('question_id', $q['id'])->delete();

            // Hapus semua matrix rows dan columns
            $matrixRowModel->where('question_id', $q['id'])->delete();
            $matrixColumnModel->where('question_id', $q['id'])->delete();
        }

        // 3️⃣ Hapus semua pertanyaan
        $questionModel->where('questionnaires_id', $id)->delete();

        // 4️⃣ Hapus semua sections
        $sectionModel->where('questionnaire_id', $id)->delete();

        // 5️⃣ Hapus semua pages
        $pageModel->where('questionnaire_id', $id)->delete();

        // 6️⃣ Terakhir hapus questionnaire
        $questionnaireModel->delete($id);

        return redirect()->to('admin/questionnaire')
            ->with('success', 'Questionnaire beserta semua relasinya berhasil dihapus.');
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
            'user_field' => 'User Profile Field',
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
        'user_field_name' => 'permit_empty|alpha_dash',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Validation errors: ' . print_r($validation->getErrors(), true));
        return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
    }

    $type = $this->request->getPost('question_type');
        if ($type === 'user_field') {
            $userFieldName = $this->request->getPost('user_field_name');
            if (empty($userFieldName)) {
                return $this->response->setJSON(['status' => 'error', 'message' => ['user_field_name' => 'User field name is required for user_field type']]);
            }
            // Validate against available fields (hardcoded for simplicity)
            $availableFields = ['nama_lengkap','email', 'nim', 'id_jurusan', 'id_prodi', 'angkatan', 'tahun_kelulusan', 'ipk', 'alamat', 'alamat2', 'kodepos', 'jenisKelamin', 'notlp', 'id_provinsi', 'id_cities'];
            if (!in_array($userFieldName, $availableFields)) {
                return $this->response->setJSON(['status' => 'error', 'message' => ['user_field_name' => 'Invalid user field name']]);
            }
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

            $data['condition_json'] = null;

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
        } elseif ($type === 'user_field') {
            $data['user_field_name'] = $this->request->getPost('user_field_name');
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
        'user_field_name' => 'permit_empty|alpha_dash',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Validation errors: ' . print_r($validation->getErrors(), true));
        return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
    }

    $type = $this->request->getPost('question_type');
        if ($type === 'user_field') {
            $userFieldName = $this->request->getPost('user_field_name');
            if (empty($userFieldName)) {
                return $this->response->setJSON(['status' => 'error', 'message' => ['user_field_name' => 'User field name is required for user_field type']]);
            }
            // Validate against available fields (hardcoded for simplicity)
            $availableFields = ['nama_lengkap','email','nim', 'id_jurusan', 'id_prodi', 'angkatan', 'tahun_kelulusan', 'ipk', 'alamat', 'alamat2', 'kodepos', 'jenisKelamin', 'notlp', 'id_provinsi', 'id_cities'];
            if (!in_array($userFieldName, $availableFields)) {
                return $this->response->setJSON(['status' => 'error', 'message' => ['user_field_name' => 'Invalid user field name']]);
            }
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

            $data['condition_json'] = null;

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
        }elseif($type === 'user_field') {
            $data['user_field_name'] = $this->request->getPost('user_field_name');
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

        log_message('debug', 'Question fetched: ' . json_encode($question));
        return $this->response->setJSON([
            'status' => 'success',
            'question' => $question
        ]);
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

    public function duplicate($questionnaire_id, $page_id, $section_id, $question_id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $questionModel = new QuestionModel();
            $optionModel = new QuestionOptionModel();
            $matrixRowModel = new MatrixRowModel();
            $matrixColumnModel = new MatrixColumnModels();

            // Ambil pertanyaan asli
            $question = $questionModel
                ->where('id', $question_id)
                ->where('questionnaires_id', $questionnaire_id)
                ->where('page_id', $page_id)
                ->where('section_id', $section_id)
                ->first();

            if (!$question) {
                log_message('error', "Question not found for ID: $question_id");
                return $this->response->setJSON(['status' => 'error', 'message' => 'Question not found']);
            }

            // Hitung order_no baru (max + 1)
            $maxOrder = $questionModel->where([
                'questionnaires_id' => $questionnaire_id,
                'page_id' => $page_id,
                'section_id' => $section_id
            ])->selectMax('order_no')->first()['order_no'] ?? 0;

            // Siapkan data untuk question baru
            $newQuestionData = [
                'questionnaires_id' => $question['questionnaires_id'],
                'page_id' => $question['page_id'],
                'section_id' => $question['section_id'],
                'question_text' => $question['question_text'] . ' (Copy)',
                'question_type' => $question['question_type'],
                'is_required' => $question['is_required'],
                'order_no' => $maxOrder + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'condition_json' => $question['condition_json'], // Copy conditional logic
            ];

            // Tambah field khusus berdasarkan tipe
            if ($question['question_type'] === 'scale') {
                $newQuestionData['scale_min'] = $question['scale_min'];
                $newQuestionData['scale_max'] = $question['scale_max'];
                $newQuestionData['scale_step'] = $question['scale_step'];
                $newQuestionData['scale_min_label'] = $question['scale_min_label'];
                $newQuestionData['scale_max_label'] = $question['scale_max_label'];
            } elseif ($question['question_type'] === 'file') {
                $newQuestionData['allowed_types'] = $question['allowed_types'];
                $newQuestionData['max_file_size'] = $question['max_file_size'];
            }

            // Insert question baru
            $newQuestionId = $questionModel->insert($newQuestionData);
            log_message('debug', 'New question inserted: ID=' . $newQuestionId);

            // Copy options (radio, checkbox, dropdown)
            if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                $options = $optionModel->where('question_id', $question_id)
                    ->orderBy('order_number', 'ASC')
                    ->findAll();

                if (!empty($options)) {
                    $optionsToInsert = [];
                    foreach ($options as $opt) {
                        $optionsToInsert[] = [
                            'question_id' => $newQuestionId,
                            'option_text' => $opt['option_text'],
                            'option_value' => $opt['option_value'],
                            'next_question_id' => $opt['next_question_id'],
                            'order_number' => $opt['order_number']
                        ];
                    }
                    $optionModel->insertBatch($optionsToInsert);
                    log_message('debug', 'Options copied: ' . json_encode($optionsToInsert));
                }
            }

            // Copy matrix rows dan columns
            if ($question['question_type'] === 'matrix') {
                $rows = $matrixRowModel->where('question_id', $question_id)
                    ->orderBy('order_no', 'ASC')
                    ->findAll();
                $columns = $matrixColumnModel->where('question_id', $question_id)
                    ->orderBy('order_no', 'ASC')
                    ->findAll();

                // Insert rows
                foreach ($rows as $row) {
                    $matrixRowModel->insert([
                        'question_id' => $newQuestionId,
                        'row_text' => $row['row_text'],
                        'order_no' => $row['order_no']
                    ]);
                }

                // Insert columns
                foreach ($columns as $col) {
                    $matrixColumnModel->insert([
                        'question_id' => $newQuestionId,
                        'column_text' => $col['column_text'],
                        'order_no' => $col['order_no']
                    ]);
                }
                log_message('debug', 'Matrix rows and columns copied for question ID: ' . $newQuestionId);
            }

            if ($db->transStatus() === false) {
                log_message('error', 'Transaction failed during duplicate');
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to duplicate question']);
            }

            $db->transComplete();
            log_message('debug', 'Question duplicated successfully: ID=' . $newQuestionId);

            // Return ID baru untuk trigger edit modal
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Question duplicated successfully',
                'new_question_id' => $newQuestionId
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Exception in duplicateSectionQuestion: ' . $e->getMessage());
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to duplicate question: ' . $e->getMessage()]);
        }
    }
    public function downloadPDF($questionnaire_id)
    {
        // Validasi akses: Asumsikan hanya admin; sesuaikan dengan sistem autentikasi Anda
        if (!session()->get('logged_in')) { // Ganti dengan pengecekan permission yang sesuai
            log_message('error', '[downloadPDF] Unauthorized access attempt for questionnaire ID: ' . $questionnaire_id);
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengunduh PDF ini.');
        }

        // $questionnaireModel = new QuestionnairModel();


        // Ambil data questionnaire
        $questionnaire = $this->questionnaireModel->find($questionnaire_id);
        if (!$questionnaire) {
            log_message('error', '[downloadPDF] Questionnaire not found: ID ' . $questionnaire_id);
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan.');
        }

        // Ambil struktur lengkap (gunakan getQuestionnaireStructure dengan data kosong untuk mendapatkan semua)
        $structure = $this->questionnaireModel->getQuestionnaireStructure($questionnaire_id, [], []);
        if (empty($structure['pages'])) {
            log_message('warning', '[downloadPDF] Empty structure for questionnaire ID: ' . $questionnaire_id);
            return redirect()->back()->with('warning', 'Kuesioner ini tidak memiliki konten. Tidak ada PDF yang dapat dibuat.');
        }

        // Log attempt
        log_message('info', '[downloadPDF] Generating PDF for questionnaire ID: ' . $questionnaire_id . ' by user ID: ' . session()->get('id'));

        try {
            // Generate HTML dari view
            $data = [
                'questionnaire' => $questionnaire,
                'structure' => $structure,
            ];
            $html = view('adminpage/questioner/pdf_template', $data);

            // Konfigurasi Dompdf
            $options = new QuestionOptionModel();
            $options->set('defaultFont', 'Helvetica');
            $options->set('isRemoteEnabled', true); // Jika ada gambar eksternal, meskipun tidak ada di sini
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // Sanitasi nama file
            $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $questionnaire['title']);
            $filename = 'questionnaire_' . $safeTitle . '_' . date('Y-m-d') . '.pdf';

            // Stream PDF (download)
            $dompdf->stream($filename, ['Attachment' => true]);
        } catch (\Exception $e) {
            log_message('error', '[downloadPDF] Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }
    public function export($id)
    {
        // Fetch questionnaire
        $questionnaire = $this->questionnaireModel->find($id);
        if (!$questionnaire) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Questionnaire not found');
        }

        // Fetch related data hierarchically
        $pages = $this->questionnairePageModel->where('questionnaire_id', $id)->orderBy('order_no', 'ASC')->findAll();
        $answers = $this->answerModel->where('questionnaire_id', $id)->findAll();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'entity_type', 'old_id', 'related_questionnaire_id', 'related_page_id', 'related_section_id', 'related_question_id', 'user_id',
            'title', 'deskripsi', 'is_active', 'conditional_logic', 'announcement', 'created_at',
            'page_title', 'order_no', 'section_title', 'question_text', 'question_type', 'condition_json',
            'scale_min', 'scale_max', 'allowed_types', 'option_text', 'option_value', 'row_text', 'column_text',
            'answer_text', 'status','user_field_name'
        ];
        $sheet->fromArray($headers, NULL, 'A1');

        $rowNum = 2;

        // Add questionnaire row
        $sheet->setCellValue('A' . $rowNum, 'questionnaire');
        $sheet->setCellValue('B' . $rowNum, $questionnaire['id']);
        $sheet->setCellValue('H' . $rowNum, $questionnaire['title']);
        $sheet->setCellValue('I' . $rowNum, $questionnaire['deskripsi']);
        $sheet->setCellValue('J' . $rowNum, $questionnaire['is_active']);
        $sheet->setCellValue('K' . $rowNum, $questionnaire['conditional_logic']);
        $sheet->setCellValue('L' . $rowNum, $questionnaire['announcement']);
        $sheet->setCellValue('M' . $rowNum, $questionnaire['created_at']);
        $rowNum++;

        // Add pages, sections, questions, options, matrix
        foreach ($pages as $page) {
            $sheet->setCellValue('A' . $rowNum, 'page');
            $sheet->setCellValue('B' . $rowNum, $page['id']);
            $sheet->setCellValue('C' . $rowNum, $page['questionnaire_id']);
            $sheet->setCellValue('N' . $rowNum, $page['page_title']);
            $sheet->setCellValue('K' . $rowNum, $page['conditional_logic']);
            $sheet->setCellValue('O' . $rowNum, $page['order_no']);
            $rowNum++;

            // Fetch sections
            $sections = $this->sectionModel->where('page_id', $page['id'])->orderBy('order_no', 'ASC')->findAll();
            foreach ($sections as $section) {
                $sheet->setCellValue('A' . $rowNum, 'section');
                $sheet->setCellValue('B' . $rowNum, $section['id']);
                $sheet->setCellValue('D' . $rowNum, $section['page_id']);
                $sheet->setCellValue('P' . $rowNum, $section['section_title']);
                $sheet->setCellValue('K' . $rowNum, $section['conditional_logic']);
                $sheet->setCellValue('O' . $rowNum, $section['order_no']);
                $rowNum++;

                // Fetch questions
                $questions = $this->questionModel->where('section_id', $section['id'])->orderBy('order_no', 'ASC')->findAll();
                foreach ($questions as $question) {
                    $sheet->setCellValue('A' . $rowNum, 'question');
                    $sheet->setCellValue('B' . $rowNum, $question['id']);
                    $sheet->setCellValue('E' . $rowNum, $question['section_id']);
                    $sheet->setCellValue('Q' . $rowNum, $question['question_text']);
                    $sheet->setCellValue('R' . $rowNum, $question['question_type']);
                    $sheet->setCellValue('S' . $rowNum, $question['condition_json']);
                    $sheet->setCellValue('O' . $rowNum, $question['order_no']);
                    $sheet->setCellValue('T' . $rowNum, $question['scale_min'] ?? '');
                    $sheet->setCellValue('U' . $rowNum, $question['scale_max'] ?? '');
                    $sheet->setCellValue('V' . $rowNum, $question['allowed_types'] ?? '');
                    $sheet->setCellValue('AC' . $rowNum, $question['user_field_name'] ?? '');
                    $rowNum++;

                    // Options for relevant types
                    if (in_array($question['question_type'], ['radio', 'checkbox', 'dropdown'])) {
                        $options = $this->questionOptionModel->where('question_id', $question['id'])->orderBy('order_number', 'ASC')->findAll();
                        foreach ($options as $option) {
                            $sheet->setCellValue('A' . $rowNum, 'option');
                            $sheet->setCellValue('B' . $rowNum, $option['id']);
                            $sheet->setCellValue('F' . $rowNum, $option['question_id']);
                            $sheet->setCellValue('W' . $rowNum, $option['option_text']);
                            $sheet->setCellValue('X' . $rowNum, $option['option_value']);
                            $sheet->setCellValue('O' . $rowNum, $option['order_number']);
                            $rowNum++;
                        }
                    }

                    // Matrix rows and columns
                    if ($question['question_type'] === 'matrix') {
                        $matrixRows = $this->matrixRowModel->where('question_id', $question['id'])->orderBy('order_no', 'ASC')->findAll();
                        foreach ($matrixRows as $matrixRow) {
                            $sheet->setCellValue('A' . $rowNum, 'matrix_row');
                            $sheet->setCellValue('B' . $rowNum, $matrixRow['id']);
                            $sheet->setCellValue('F' . $rowNum, $matrixRow['question_id']);
                            $sheet->setCellValue('Y' . $rowNum, $matrixRow['row_text']);
                            $sheet->setCellValue('O' . $rowNum, $matrixRow['order_no']);
                            $rowNum++;
                        }

                        $matrixColumns = $this->matrixColumnModel->where('question_id', $question['id'])->orderBy('order_no', 'ASC')->findAll();
                        foreach ($matrixColumns as $matrixColumn) {
                            $sheet->setCellValue('A' . $rowNum, 'matrix_column');
                            $sheet->setCellValue('B' . $rowNum, $matrixColumn['id']);
                            $sheet->setCellValue('F' . $rowNum, $matrixColumn['question_id']);
                            $sheet->setCellValue('Z' . $rowNum, $matrixColumn['column_text']);
                            $sheet->setCellValue('O' . $rowNum, $matrixColumn['order_no']);
                            $rowNum++;
                        }
                    }
                }
            }
        }

        // Add answers
        foreach ($answers as $answer) {
            $sheet->setCellValue('A' . $rowNum, 'answer');
            $sheet->setCellValue('B' . $rowNum, $answer['id']);
            $sheet->setCellValue('C' . $rowNum, $answer['questionnaire_id']);
            $sheet->setCellValue('F' . $rowNum, $answer['question_id']);
            $sheet->setCellValue('G' . $rowNum, $answer['user_id']);
            $sheet->setCellValue('AA' . $rowNum, $answer['answer_text']);
            $sheet->setCellValue('AB' . $rowNum, $answer['STATUS']);
            $rowNum++;
        }

        // Output the file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="questionnaire_export_' . $id . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }
    public function import()
    {
        $this->logger->info('Import method called at ' . date('Y-m-d H:i:s'));

        // Step 1: Verify file upload
        $file = $this->request->getFile('excel_file');
        if (!$file) {
            $this->logger->error('No file uploaded');
            session()->setFlashdata('error', 'No file uploaded.');
            return redirect()->to('admin/questionnaire');
        }

        $this->logger->info('File received: ' . $file->getName() . ', Size: ' . $file->getSize() . ' bytes');

        if (!$file->isValid() || $file->getExtension() !== 'xlsx') {
            $this->logger->error('Invalid file: Valid=' . ($file->isValid() ? 'true' : 'false') . ', Extension=' . $file->getExtension());
            session()->setFlashdata('error', 'Invalid Excel file. Must be a valid .xlsx file.');
            return redirect()->to('admin/questionnaire');
        }

        // Step 2: Load spreadsheet
        try {
            $reader = new XlsxReader();
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
            $this->logger->info('Excel file loaded successfully. Row count: ' . count($rows));
        } catch (Exception $e) {
            $this->logger->error('Failed to load Excel file: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to read Excel file: ' . $e->getMessage());
            return redirect()->to('admin/questionnaire');
        }

        // Step 3: Parse and group rows
        array_shift($rows); // Remove header row
        $entities = [
            'questionnaire' => [],
            'page' => [],
            'section' => [],
            'question' => [],
            'option' => [],
            'matrix_row' => [],
            'matrix_column' => [],
            'answer' => [],
        ];
        foreach ($rows as $rowNum => $row) {
            $type = trim($row['A'] ?? '');
            if ($type && array_key_exists($type, $entities)) {
                $entities[$type][] = $row;
            } else {
                $this->logger->warning('Invalid entity_type in row ' . ($rowNum + 2) . ': ' . $type);
            }
        }
        $this->logger->info('Entities grouped: ' . json_encode(array_map('count', $entities)));

        // Step 4: Validate questionnaire
        if (count($entities['questionnaire']) !== 1) {
            $this->logger->error('Invalid questionnaire count: ' . count($entities['questionnaire']));
            session()->setFlashdata('error', 'Excel must contain exactly one questionnaire.');
            return redirect()->to('admin/questionnaire');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Step 5: Insert questionnaire
            $qRow = $entities['questionnaire'][0];
            $qData = [
                'title' => $qRow['H'] ?? '',
                'deskripsi' => $qRow['I'] ?? '',
                'is_active' => $qRow['J'] ?? 'draft',
                'conditional_logic' => $qRow['K'] ?? null,
                'announcement' => $qRow['L'] ?? '',
                'created_at' => $qRow['M'] ?? date('Y-m-d H:i:s'),
            ];
            if (empty($qData['title'])) {
                $this->logger->error('Missing questionnaire title');
                throw new Exception('Missing required questionnaire title.');
            }
            $this->logger->info('Inserting questionnaire: ' . json_encode($qData));
            $this->questionnaireModel->insert($qData);
            $newQuestionnaireId = $this->questionnaireModel->getInsertID();
            $this->logger->info('Questionnaire inserted, new ID: ' . $newQuestionnaireId);
            $idMap = [
                'questionnaire' => [$qRow['B'] => $newQuestionnaireId],
                'page' => [],
                'section' => [],
                'question' => [],
                'option' => [],
                'matrix_row' => [],
                'matrix_column' => [],
            ];

            // Step 6: Insert pages
            foreach ($entities['page'] as $pRow) {
                if (empty($pRow['N']) || empty($pRow['O'])) {
                    $this->logger->error('Missing required fields for page in row: ' . json_encode($pRow));
                    throw new Exception('Missing required fields for page.');
                }
                $pData = [
                    'questionnaire_id' => $newQuestionnaireId,
                    'page_title' => $pRow['N'],
                    'conditional_logic' => $pRow['K'] ?? null,
                    'order_no' => (int)$pRow['O'],
                ];
                $this->logger->info('Inserting page: ' . json_encode($pData));
                $this->questionnairePageModel->insert($pData);
                $newPageId = $this->questionnairePageModel->getInsertID();
                $idMap['page'][$pRow['B']] = $newPageId;
                $this->logger->info('Page inserted, old_id: ' . $pRow['B'] . ', new_id: ' . $newPageId);
            }

            // Step 7: Insert sections
            foreach ($entities['section'] as $sRow) {
                $oldPageId = $sRow['D'] ?? '';
                if (empty($idMap['page'][$oldPageId]) || empty($sRow['P']) || empty($sRow['O'])) {
                    $this->logger->error('Invalid or missing fields for section in row: ' . json_encode($sRow));
                    throw new Exception('Invalid or missing fields for section.');
                }
                $sData = [
                    'questionnaire_id' => $newQuestionnaireId,  // Satisfies FK constraint
                    'page_id' => $idMap['page'][$oldPageId],
                    'section_title' => $sRow['P'],
                    'conditional_logic' => $sRow['K'] ?? null,
                    'order_no' => (int)$sRow['O'],
                ];
                $this->logger->info('Inserting section: ' . json_encode($sData));
                $this->sectionModel->insert($sData);
                $newSectionId = $this->sectionModel->getInsertID();
                $idMap['section'][$sRow['B']] = $newSectionId;
                $this->logger->info('Section inserted, old_id: ' . $sRow['B'] . ', new_id: ' . $newSectionId);
            }

            // Step 8: Insert questions
            foreach ($entities['question'] as $qRow) {
                $oldSectionId = $qRow['E'] ?? '';
                if (empty($idMap['section'][$oldSectionId]) || empty($qRow['Q']) || empty($qRow['R']) || empty($qRow['O'])) {
                    $this->logger->error('Invalid or missing fields for question in row: ' . json_encode($qRow));
                    throw new Exception('Invalid or missing fields for question.');
                }
                if (!in_array($qRow['R'], ['text', 'textarea', 'email', 'number', 'radio', 'checkbox', 'dropdown', 'date', 'scale', 'matrix', 'file', 'user_field'])) {
                    $this->logger->error('Invalid question type: ' . $qRow['R']);
                    throw new Exception('Invalid question type.');
                }
                $qData = [
                    'questionnaires_id' => $newQuestionnaireId,  // Satisfies FK constraint (note: plural 'questionnaires_id')
                    'section_id' => $idMap['section'][$oldSectionId],
                    'question_text' => $qRow['Q'],
                    'question_type' => $qRow['R'],
                    'condition_json' => $qRow['S'] ?? null,
                    'order_no' => (int)$qRow['O'],
                    'scale_min' => $qRow['T'] ? (int)$qRow['T'] : null,
                    'scale_max' => $qRow['U'] ? (int)$qRow['U'] : null,
                    'allowed_types' => $qRow['V'] ?? null,
                    'user_field_name' => $qRow['AC'] ?? null,   
                    'is_required' => 1,  // Default to required; adjust if needed based on your app logic
                    'created_at' => date('Y-m-d H:i:s'),  // Set explicit timestamp
                ];
                $this->logger->info('Inserting question: ' . json_encode($qData));
                $this->questionModel->insert($qData);
                $newQuestionId = $this->questionModel->getInsertID();
                $idMap['question'][$qRow['B']] = $newQuestionId;
                $this->logger->info('Question inserted, old_id: ' . $qRow['B'] . ', new_id: ' . $newQuestionId);
            }

            // Step 9: Insert options
            foreach ($entities['option'] as $oRow) {
                $oldQuestionId = $oRow['F'] ?? '';
                if (empty($idMap['question'][$oldQuestionId]) || empty($oRow['W']) || empty($oRow['O'])) {
                    $this->logger->error('Invalid or missing fields for option in row: ' . json_encode($oRow));
                    throw new Exception('Invalid or missing fields for option.');
                }
                $oData = [
                    'question_id' => $idMap['question'][$oldQuestionId],
                    'option_text' => $oRow['W'],
                    'option_value' => $oRow['X'] ?? '',
                    'order_number' => (int)$oRow['O'],
                ];
                $this->logger->info('Inserting option: ' . json_encode($oData));
                $this->questionOptionModel->insert($oData);
                $this->logger->info('Option inserted for question_id: ' . $oData['question_id']);
            }

            // Step 10: Insert matrix rows
            foreach ($entities['matrix_row'] as $mrRow) {
                $oldQuestionId = $mrRow['F'] ?? '';
                if (empty($idMap['question'][$oldQuestionId]) || empty($mrRow['Y']) || empty($mrRow['O'])) {
                    $this->logger->error('Invalid or missing fields for matrix_row in row: ' . json_encode($mrRow));
                    throw new Exception('Invalid or missing fields for matrix_row.');
                }
                $mrData = [
                    'question_id' => $idMap['question'][$oldQuestionId],
                    'row_text' => $mrRow['Y'],
                    'order_no' => (int)$mrRow['O'],
                ];
                $this->logger->info('Inserting matrix_row: ' . json_encode($mrData));
                $this->matrixRowModel->insert($mrData);
                $this->logger->info('Matrix row inserted for question_id: ' . $mrData['question_id']);
            }

            // Step 11: Insert matrix columns
           $matrixColumnsByQuestion = [];
        foreach ($entities['matrix_column'] as $mcRow) {
            $oldQuestionId = $mcRow['F'] ?? '';
            if (empty($idMap['question'][$oldQuestionId])) {
                $this->logger->warning('Skipping matrix_column due to invalid question_id in row: ' . json_encode($mcRow));
                continue;  // Skip if question not mapped (e.g., failed question insert)
            }
            if (empty($mcRow['Z'])) {  // column_text is required
                $this->logger->error('Missing column_text for matrix_column in row: ' . json_encode($mcRow));
                throw new Exception('Missing required column_text for matrix_column.');
            }
            $matrixColumnsByQuestion[$oldQuestionId][] = $mcRow;
        }

        foreach ($matrixColumnsByQuestion as $oldQuestionId => $mcRows) {
            $newQuestionId = $idMap['question'][$oldQuestionId];
            $currentOrder = 1;  // Start sequential order_no for this question's columns
            foreach ($mcRows as $mcRow) {
                $orderNo = !empty($mcRow['O']) ? (int)$mcRow['O'] : $currentOrder;  // Use provided or sequential
                $mcData = [
                    'question_id' => $newQuestionId,
                    'column_text' => $mcRow['Z'],
                    'order_no' => $orderNo,
                ];
                $this->logger->info('Inserting matrix_column: ' . json_encode($mcData) . ' (order_no: ' . ($mcRow['O'] ? 'from Excel' : 'sequential') . ')');
                $this->matrixColumnModel->insert($mcData);
                $this->logger->info('Matrix column inserted for question_id: ' . $mcData['question_id']);
                $currentOrder++;  // Increment for next column
            }
        }

            // Step 12: Insert answers
            foreach ($entities['answer'] as $aRow) {
                $oldQuestionId = $aRow['F'] ?? '';
                if (empty($idMap['question'][$oldQuestionId]) || empty($aRow['G']) || empty($aRow['AA'])) {
                    $this->logger->warning('Skipping answer due to invalid or missing fields in row: ' . json_encode($aRow));
                    continue; // Skip invalid answers to avoid breaking the transaction
                }
                $aData = [
                    'questionnaire_id' => $newQuestionnaireId,
                    'user_id' => (int)$aRow['G'],
                    'question_id' => $idMap['question'][$oldQuestionId],
                    'answer_text' => $aRow['AA'],
                    'STATUS' => $aRow['AB'] ?? '',
                ];
                $this->logger->info('Inserting answer: ' . json_encode($aData));
                $this->answerModel->insert($aData);
                $this->logger->info('Answer inserted for question_id: ' . $aData['question_id']);
            }

            foreach ($idMap['page'] as $oldPageId => $newPageId) {
            $page = $this->questionnairePageModel->find($newPageId);
            if ($page && $page['conditional_logic']) {
                $logicArr = json_decode($page['conditional_logic'], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($logicArr['conditions'])) {
                    foreach ($logicArr['conditions'] as &$condition) {
                        $field = $condition['field'] ?? '';
                        if (is_numeric($field) && isset($idMap['question'][$field])) {
                            $condition['field'] = (string) $idMap['question'][$field];
                            $this->logger->info("Updated page conditional_logic field from {$field} to {$idMap['question'][$field]} for page ID: {$newPageId}");
                        } else {
                            $this->logger->warning("No mapping found for field {$field} in page ID: {$newPageId}");
                        }
                    }
                    $updatedLogic = json_encode($logicArr);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->questionnairePageModel->update($newPageId, ['conditional_logic' => $updatedLogic]);
                        $this->logger->info("Updated conditional_logic for page ID: {$newPageId} (old ID: {$oldPageId})");
                    } else {
                        $this->logger->error("Failed to encode JSON for page ID: {$newPageId}");
                        throw new Exception("Failed to encode JSON for page ID: {$newPageId}");
                    }
                } else {
                    $this->logger->warning("Invalid JSON in conditional_logic for page ID: {$newPageId}");
                }
            }
        }

        // Step 14: Update conditional_logic for sections (remap question IDs in JSON)
        foreach ($idMap['section'] as $oldSectionId => $newSectionId) {
            $section = $this->sectionModel->find($newSectionId);
            if ($section && $section['conditional_logic']) {
                $logicArr = json_decode($section['conditional_logic'], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($logicArr['conditions'])) {
                    foreach ($logicArr['conditions'] as &$condition) {
                        $field = $condition['field'] ?? '';
                        if (is_numeric($field) && isset($idMap['question'][$field])) {
                            $condition['field'] = (string) $idMap['question'][$field];
                            $this->logger->info("Updated section conditional_logic field from {$field} to {$idMap['question'][$field]} for section ID: {$newSectionId}");
                        } else {
                            $this->logger->warning("No mapping found for field {$field} in section ID: {$newSectionId}");
                        }
                    }
                    $updatedLogic = json_encode($logicArr);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->sectionModel->update($newSectionId, ['conditional_logic' => $updatedLogic]);
                        $this->logger->info("Updated conditional_logic for section ID: {$newSectionId} (old ID: {$oldSectionId})");
                    } else {
                        $this->logger->error("Failed to encode JSON for section ID: {$newSectionId}");
                        throw new Exception("Failed to encode JSON for section ID: {$newSectionId}");
                    }
                } else {
                    $this->logger->warning("Invalid JSON in conditional_logic for section ID: {$newSectionId}");
                }
            }
        }

            $db->transCommit();
            $this->logger->info('Transaction committed successfully.');
            session()->setFlashdata('success', 'Questionnaire imported successfully.');
        } catch (Exception $e) {
            $db->transRollback();
            $this->logger->error('Import failed: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            session()->setFlashdata('error', 'Import failed: ' . $e->getMessage());
        }

        return redirect()->to('admin/questionnaire');
    }
}
