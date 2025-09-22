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
use App\Models\AnswerModel;

class KaprodiQuestionnairController extends BaseController
{
    public function index()
    {
        $questionnaireModel = new QuestionnairModel();
        $prodiModel = new Prodi();

        // Ambil id_prodi dari session (saat kaprodi login)
        $id_prodi = session()->get('id_prodi');

        // Ambil data prodi berdasarkan id_prodi
        $kaprodi = $prodiModel->find($id_prodi);

        if (!$kaprodi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data prodi tidak ditemukan.');
        }

        // Gunakan langsung dari session, bukan dari array $kaprodi
        $user_data = [
            'id_prodi' => $id_prodi,
        ];

        // khusus kaprodi → filter prodi dan wajib ada conditional_logic
        $kuesioner = $questionnaireModel->getAccessibleQuestionnaires($user_data, 'kaprodi');

        return view('kaprodi/kuesioner/index', [
            'kaprodi'        => $kaprodi,
            'questionnaires' => $kuesioner
        ]);
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
            'id_role',
            'nama_lengkap',
            'nim',
            'id_jurusan',
            // 'id_prodi',
            'angkatan',
            'ipk',
            'alamat',
            'alamat2',
            'id_cities',
            'kodepos',
            'tahun_kelulusan',
            'jenisKelamin',
            'no_tlp'
        ];

        // Ambil semua kuesioner milik prodi kaprodi
        $questionnaireModel = new \App\Models\QuestionnairModel();
        $questionnaires = $questionnaireModel
            ->where('id_prodi', $this->kaprodi['id_prodi'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Kirim data ke view, termasuk data kaprodi
        return view('kaprodi/kuesioner/tambah', [
            'fields' => $user_fields,
            'operators' => $operators,
            'questionnaires' => $questionnaires,
            'kaprodi' => $this->kaprodi // <-- ini tambahan
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
            case 'id_role':
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

    protected $kaprodi;
    protected $db;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        // Panggil parent initController
        parent::initController($request, $response, $logger);

        // Koneksi database
        $this->db = \Config\Database::connect();

        // Ambil id_account dari session
        $idAccount = session()->get('id_account');

        if (!$idAccount || session()->get('role_id') != 6) {
            // Jika belum login atau bukan kaprodi
            throw new \Exception("Akses ditolak: kaprodi belum login.");
        }

        // Ambil data kaprodi beserta prodi
        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, p.id as id_prodi');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        if (!$kaprodi) {
            throw new \Exception("Akses ditolak: data kaprodi tidak ditemukan.");
        }

        // Set properti kaprodi
        $this->kaprodi = $kaprodi;
    }


    // Override store() khusus Kaprodi
    public function store()
    {
        $questionnaireModel = new \App\Models\QuestionnairModel();

        $title = $this->request->getPost('title');
        $description = $this->request->getPost('deskripsi');
        $is_active = $this->request->getPost('is_active'); // enum string
        $conditionalLogic = null;

        // Handle conditional logic
        if ($this->request->getPost('conditional_logic')) {
            $fields    = $this->request->getPost('field_name');
            $operators = $this->request->getPost('operator');
            $values    = $this->request->getPost('value');

            $conditions = [];
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[$i]) && !empty($operators[$i]) && !empty($values[$i])) {
                    // Jika field adalah prodi/jurusan, pastikan value sesuai kaprodi
                    if (in_array($fields[$i], ['id_prodi', 'id_jurusan'])) {
                        if ($values[$i] != $this->kaprodi['id_prodi']) {
                            $values[$i] = $this->kaprodi['id_prodi']; // paksa sesuai prodi kaprodi
                        }
                    }

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
            'is_active'         => $is_active,
            'id_prodi'          => $this->kaprodi['id_prodi'], // otomatis dari kaprodi
            'conditional_logic' => $conditionalLogic,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        // Redirect ke create lagi, agar tetap di halaman tambah kuesioner
        return redirect()->to(base_url('/kaprodi/kuesioner'))
            ->with('success', 'Kuesioner berhasil dibuat!');
    }



    // Update Kuesioner
    public function update($questionnaire_id)
    {
        $model = new \App\Models\QuestionnairModel();
        $questionnaire = $model->find($questionnaire_id);

        if (!$questionnaire) {
            return redirect()->to('/kaprodi/questioner')->with('error', 'Data tidak ditemukan.');
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
                    // Pastikan field prodi/jurusan tetap sesuai kaprodi
                    if (in_array($fields[$i], ['id_prodi', 'id_jurusan'])) {
                        $values[$i] = $this->kaprodi['id_prodi'];
                    }

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

        // Update data, id_prodi tetap dari kaprodi dan tidak bisa diubah
        $model->update($questionnaire_id, [
            'title'             => $this->request->getPost('title'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            'is_active'         => $is_active,
            'conditional_logic' => $conditionalLogic,
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        // Tetap di halaman edit agar user bisa lihat hasilnya
        return redirect()->to('/kaprodi/kuesioner/' . $questionnaire_id . '/edit')
            ->with('success', 'Kuesioner berhasil diperbarui!');
    }




    // Halaman Edit Kuesioner
    public function edit($questionnaire_id)
    {
        $model = new \App\Models\QuestionnairModel();
        $questionnaire = $model->find($questionnaire_id);

        if (!$questionnaire) {
            return redirect()->to('/kaprodi/kuesioner')->with('error', 'Data tidak ditemukan.');
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
            'id_role',
            'nama_lengkap',
            'nim',
            'id_jurusan',
            'id_prodi',
            'angkatan',
            'ipk',
            'alamat',
            'alamat2',
            'id_cities',
            'kodepos',
            'tahun_kelulusan',
            'jenisKelamin',
            'no_tlp'
        ];

        // Decode conditional logic jika ada
        $conditionalLogic = [];
        if (!empty($questionnaire['conditional_logic'])) {
            $conditionalLogic = json_decode($questionnaire['conditional_logic'], true);

            // Pastikan field prodi/jurusan tetap sesuai kaprodi
            foreach ($conditionalLogic as &$cond) {
                if (in_array($cond['field'], ['id_prodi', 'id_jurusan'])) {
                    $cond['value'] = $this->kaprodi['id_prodi'];
                }
            }
        }

        return view('kaprodi/kuesioner/edit', [
            'questionnaire' => $questionnaire,
            'conditionalLogic' => $conditionalLogic,
            'fields' => $user_fields,
            'operators' => $operators
        ]);
    }







    public function delete($id)
    {
        // Cek apakah ada answers terkait
        $questionModel = new QuestionModel();
        $questions = $questionModel->where('questionnaires_id', $id)->findAll();
        $hasAnswers = false;
        $answerModel = new AnswerModel(); // Asumsi model AnswerModel ada di App\Models\AnswerModel

        foreach ($questions as $q) {
            $answerCount = $answerModel->where('question_id', $q['id'])->countAllResults();
            if ($answerCount > 0) {
                $hasAnswers = true;
                break;
            }
        }

        // Konfirmasi kedua via parameter GET 'confirm' (dari JS di view)
        $confirm = $this->request->getGet('confirm');
        if ($hasAnswers && !$confirm) {
            // Redirect dengan parameter untuk konfirmasi
            return redirect()->to(current_url() . '?confirm=1')->with('warning', 'Questionnaire ini memiliki jawaban terkait. Apakah Anda yakin ingin menghapus? Jawaban akan ikut terhapus.');
        }

        // Lanjut hapus
        $questionnaireModel = new QuestionnairModel();
        $pageModel = new QuestionnairePageModel();
        $sectionModel = new SectionModel();
        $questionModel = new QuestionModel();
        $optionModel = new QuestionOptionModel();
        $matrixRowModel = new MatrixRowModel();
        $matrixColumnModel = new MatrixColumnModels(); // Sesuaikan nama model

        // Loop hapus relasi setiap pertanyaan, termasuk answers
        foreach ($questions as $q) {
            // Hapus answers untuk question ini (baru ditambah)
            $answerModel->where('question_id', $q['id'])->delete();

            // Hapus options dari question_options
            $optionModel->where('question_id', $q['id'])->delete();

            // Hapus matrix rows dari matrix_rows
            $matrixRowModel->where('question_id', $q['id'])->delete();

            // Hapus matrix columns dari matrix_columns
            $matrixColumnModel->where('question_id', $q['id'])->delete();
        }

        // Hapus pertanyaan dari questions
        $questionModel->where('questionnaires_id', $id)->delete();

        // Hapus section dari questionnaire_sections
        $sectionModel->where('questionnaire_id', $id)->delete();

        // Hapus page dari questionnaire_pages
        $pageModel->where('questionnaire_id', $id)->delete();

        // Terakhir hapus questionnaire dari questionnaires
        $questionnaireModel->delete($id);

        return redirect()->to('kaprodi/kuesioner')->with('success', 'Data dan relasinya berhasil dihapus.');
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
            return redirect()->to('kaprodi/kuesioner')
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

        return view('kaprodi/kuesioner/question/index', [
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
}
