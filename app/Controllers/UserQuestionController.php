<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnswerModel;
use App\Models\DetailaccountAlumni;
use App\Models\QuestionnairModel;
use App\Models\QuestionModel;
use App\Models\QuestionnairePageModel;
use App\Models\QuestionnairConditionModel;
use App\models\LogActivityModel;
use App\Models\AccountModel;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Provincies;
use App\Models\Cities;



class UserQuestionController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel;
    protected $conditionModel;
    protected $logActivityModel;
    protected $accountModel;
    protected $detailAccountAlumniModel;

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel = new AnswerModel();
        $this->conditionModel = new QuestionnairConditionModel();
        $this->logActivityModel = new LogActivityModel();
        $this->accountModel = new AccountModel();
        $this->detailAccountAlumniModel = new DetailaccountAlumni();
    }

    /**
     * FIXED: Daftar semua kuesioner yang bisa diakses user
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId   = session()->get('id');
        $userData = session()->get();

        $userId = session()->get('id_account'); // Assume logged in user
        $detailModel = new DetailaccountAlumni();
        $userProfile = $detailModel->where('id_account', $userId)->first() ?? [];

        log_message('debug', '[index] User Data for conditional check: ' . print_r($userData, true));

        $questionnaires = $this->questionnaireModel->getAccessibleQuestionnaires($userData);
        log_message('debug', '[index] Accessible questionnaires count: ' . count($questionnaires));

        $data = [];
        foreach ($questionnaires as $q) {
            if ($q['is_active'] === 'inactive') {
                log_message('debug', '[index] Skipping inactive questionnaire ID: ' . $q['id']);
                continue;
            }

            // FIXED: Map internal status to expected view status
            $internalStatus = $this->answerModel->getStatus($q['id'], $userId) ?: 'draft';
            $statusPengisian = $this->mapStatusForView($internalStatus, $q['id'], $userId);
            
            // FIXED: Calculate progress based on status and logical completion
            $progress = $this->calculateProgressForView($statusPengisian, $q['id'], $userId, $userData);

            log_message('debug', '[index] Questionnaire ' . $q['id'] . ' - Internal Status: ' . $internalStatus . ', View Status: ' . $statusPengisian . ', Progress: ' . $progress);

            $data[] = [
                'id'          => $q['id'],
                'judul'       => $q['title'],
                'statusIsi'   => $statusPengisian,
                'progress'    => $progress,
                'is_active'   => $q['is_active'],
                'conditional' => $q['conditional_logic'] ?? '-',
                'user_profile' => $userProfile,
            ];
        }

        log_message('debug', '[index] Final data for view: ' . print_r($data, true));

        return view('alumni/questioner/index', ['data' => $data]);
    }

    /**
     * NEW: Map internal status values to view-expected status values
     */
    private function mapStatusForView($internalStatus, $questionnaireId, $userId)
    {
        switch ($internalStatus) {
            case 'completed':
                return 'Finish';
            case 'draft':
                // Check if user has any answers - if yes, it's "On Going"
                $hasAnswers = $this->answerModel->where([
                    'questionnaire_id' => $questionnaireId,
                    'user_id' => $userId
                ])->countAllResults() > 0;
                
                return $hasAnswers ? 'On Going' : 'Belum Mengisi';
            default:
                return 'Belum Mengisi';
        }
    }

    /**
     * NEW: Calculate progress appropriate for the view status
     */
    private function calculateProgressForView($viewStatus, $questionnaireId, $userId, $userData)
    {
        if ($viewStatus === 'Finish') {
            return 100;
        } elseif ($viewStatus === 'On Going') {
            // Use enhanced logical progress calculation
            $previousAnswers = $this->answerModel->getUserAnswers($questionnaireId, $userId);
            $structure = $this->questionnaireModel->getQuestionnaireStructure($questionnaireId, $userData, $previousAnswers);
            
            if (!empty($structure)) {
                return $this->calculateLogicalProgressForUser($structure, $previousAnswers);
            }
            
            // Fallback to simple progress
            return $this->answerModel->getProgress($questionnaireId, $userId);
        } else {
            return 0;
        }
    }

    private function sanitizeAnnouncementContent($content)
    {
        if (empty($content)) {
            return '';
        }
        
        // List of allowed HTML tags for announcement content
        $allowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li', 'blockquote', 'span'
        ];
        
        // Convert allowed tags array to string format for strip_tags
        $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
        
        // Strip unwanted tags while keeping allowed ones
        $cleanContent = strip_tags($content, $allowedTagsString);
        
        // Remove empty paragraphs that only contain &nbsp; or whitespace
        $cleanContent = preg_replace('/<p[^>]*>(\s|&nbsp;)*<\/p>/i', '', $cleanContent);
        
        // Clean up multiple consecutive <br> tags
        $cleanContent = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br><br>', $cleanContent);
        
        // Remove any remaining empty paragraphs
        $cleanContent = preg_replace('/<p[^>]*><\/p>/i', '', $cleanContent);
        
        // Trim whitespace
        $cleanContent = trim($cleanContent);
        
        return $cleanContent;
    }

    /**
     * ENHANCED: Mulai isi kuesioner dengan better debugging
     */
    public function mulai($q_id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil user ID dari session (konsisten dengan id_account)
        $userId = session()->get('id_account');
        $userData = session()->get();

        // Inisialisasi model
        $jurusanModel = new Jurusan();
        $prodiModel = new Prodi();
        $provinciesModel = new Provincies();
        $citiesModel = new Cities();

        // Ambil data user dari tabel account dan detailaccount_alumni
        $userAccount = $this->accountModel->find($userId);
        $userDetail = $this->detailAccountAlumniModel->where('id_account', $userId)->first() ?? [];

        // Siapkan data profil pengguna
        $userProfile = $userDetail; // Mulai dengan data dari detailaccount_alumni
        $userProfileDisplay = $userDetail;

        // Tambahkan email dari tabel account
        if ($userAccount) {
            $userProfile['email'] = $userAccount['email'];
            $userProfileDisplay['email'] = $userAccount['email']; // Nilai display sama dengan aslinya untuk non-foreign key
        } else {
            log_message('warning', '[mulai] User account not found for ID: ' . $userId);
            $userProfile['email'] = ''; // Fallback untuk menghindari error
            $userProfileDisplay['email'] = ''; // Fallback
        }

        // Proses display value untuk foreign key (jurusan, cities, dll.)
        if (!empty($userProfile['id_jurusan'])) {
            $jurusan = $jurusanModel->find($userProfile['id_jurusan']);
            $userProfileDisplay['id_jurusan_name'] = $jurusan['nama_jurusan'] ?? 'Tidak diketahui';
        }
        if (!empty($userProfile['id_cities'])) {
            $city = $citiesModel->find($userProfile['id_cities']);
            $userProfileDisplay['id_cities_name'] = $city['name'] ?? 'Tidak diketahui';
        }
        if (!empty($userProfile['id_prodi'])) {
            $prodi = $prodiModel->find($userProfile['id_prodi']);
            $userProfileDisplay['id_prodi_name'] = $prodi['nama_prodi'] ?? 'Tidak diketahui';
        }
        if (!empty($userProfile['id_provinsi'])) {
            $provinsi = $provinciesModel->find($userProfile['id_provinsi']);
            $userProfileDisplay['id_provinsi_name'] = $provinsi['name'] ?? 'Tidak diketahui';
        }

        // Ambil opsi untuk foreign key
        $jurusanOptions = $jurusanModel->findAll();
        $citiesOptions = $citiesModel->findAll();
        $prodiOptions = $prodiModel->findAll();
        $provinsiOptions = $provinciesModel->findAll();

        // Mapping friendly names dan types (termasuk email)
        $fieldFriendlyNames = [
            'nama_lengkap' => 'Nama Lengkap',
            'nim' => 'NIM',
            'id_jurusan' => 'ID Jurusan',
            'id_prodi' => 'ID Prodi',
            'angkatan' => 'Angkatan',
            'tahun_kelulusan' => 'Tahun Kelulusan',
            'ipk' => 'IPK',
            'alamat' => 'Alamat',
            'alamat2' => 'Alamat 2',
            'kodepos' => 'Kode Pos',
            'jenisKelamin' => 'Jenis Kelamin',
            'notlp' => 'No. Telepon',
            'id_provinsi' => 'ID Provinsi',
            'id_cities' => 'ID Kota',
            'email' => 'Email',
        ];

        $fieldTypes = [
            'nama_lengkap' => 'text',
            'id_jurusan' => 'foreign_key:jurusan',
            'id_cities' => 'foreign_key:cities',
            'jenisKelamin' => 'text',
            'id_prodi' => 'foreign_key:prodi',
            'id_provinsi' => 'foreign_key:provincies',
            'angkatan' => 'number',
            'tahun_kelulusan' => 'number',
            'ipk' => 'decimal',
            'alamat' => 'text',
            'alamat2' => 'text',
            'kodepos' => 'number',
            'notlp' => 'text',
            'nim' => 'number',
            'email' => 'email',
        ];

        $q_id = (int)$q_id;

        log_message('debug', '[mulai] Starting questionnaire ' . $q_id . ' for user ' . $userId);
        log_message('debug', '[mulai] UserData: ' . print_r($userData, true));
        log_message('debug', '[mulai] UserProfile: ' . print_r($userProfile, true));
        log_message('debug', '[mulai] UserProfileDisplay: ' . print_r($userProfileDisplay, true));

        // Check if we should show announcement
        $showAnnouncement = $this->request->getGet('show_announcement') === '1' || session()->getFlashdata('show_announcement');
        $announcementContent = session()->getFlashdata('announcement_content');
        $questionnaireTitle = session()->getFlashdata('questionnaire_title');

        if ($showAnnouncement && !empty($announcementContent)) {
            log_message('debug', '[mulai] Showing announcement for questionnaire ' . $q_id);

            // Clean and sanitize HTML content from TinyMCE
            $cleanedContent = $this->sanitizeAnnouncementContent($announcementContent);

            return view('alumni/questioner/announcement', [
                'q_id' => $q_id,
                'questionnaire_title' => $questionnaireTitle,
                'announcement_content' => $cleanedContent
            ]);
        }

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire) {
            log_message('error', '[mulai] Questionnaire not found for ID: ' . $q_id);
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan.');
        }

        // Enhanced access check with debugging
        $hasAccess = $this->questionnaireModel->checkConditions($questionnaire['conditional_logic'] ?? '', $userData);
        log_message('debug', '[mulai] Access check result: ' . ($hasAccess ? 'GRANTED' : 'DENIED'));

        if (!$hasAccess) {
            log_message('warning', '[mulai] Access denied for questionnaire ' . $q_id . ' user ' . $userId);
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kuesioner ini.');
        }

        // Check status with proper mapping
        $internalStatus = $this->answerModel->getStatus($q_id, $userId);
        $viewStatus = $this->mapStatusForView($internalStatus, $q_id, $userId);

        log_message('debug', '[mulai] Status check - Internal: ' . $internalStatus . ', View: ' . $viewStatus);

        if ($viewStatus === 'Finish') {
            return redirect()->to("/alumni/questioner/lihat/$q_id");
        }

        // Get previous answers and structure
        $previous_answers = $this->answerModel->getUserAnswers($q_id, $userId);
        log_message('debug', '[mulai] Previous answers count: ' . count($previous_answers));

        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $userData, $previous_answers);
        log_message('debug', '[mulai] Structure pages count: ' . count($structure['pages'] ?? []));

        if (empty($structure['pages'])) {
            log_message('warning', '[mulai] No pages available for questionnaire ' . $q_id);
            session()->setFlashdata('no_questions_available', true);
            return redirect()->to('alumni/questionnaires');
        }

        $progress = $this->calculateLogicalProgressForUser($structure, $previous_answers);
        log_message('debug', '[mulai] Calculated progress: ' . $progress . '%');

        session()->set("current_q_id", $q_id);

        return view('alumni/questioner/fill', [
            'structure'        => $structure,
            'user_id'          => $userId,
            'q_id'             => $q_id,
            'progress'         => $progress,
            'previous_answers' => $previous_answers,
            'user_profile'     => $userProfile,
            'field_friendly_names' => $fieldFriendlyNames,
            'field_types'      => $fieldTypes,
            'jurusan_options'  => $jurusanOptions,
            'cities_options'   => $citiesOptions,
            'prodi_options'    => $prodiOptions,
            'provinsi_options' => $provinsiOptions,
            'user_profile_display' => $userProfileDisplay,
        ]);
    }

    /**
     * Lanjutkan isi kuesioner
     */
    public function lanjutkan($q_id)
    {
        return $this->mulai($q_id);
    }

    /**
     * Review / lihat hasil kuesioner
     */
    public function lihat($q_id)
    {
        $user_data = session()->get();

        $data['structure'] = $this->questionnaireModel->getQuestionnaireStructure(
            $q_id,
            $user_data,
            $this->answerModel->getUserAnswers($q_id, $user_data['id'])
        );

        if (!$data['structure']) {
            return redirect()->to('/alumni/questioner')
                ->with('error', 'Kuesioner tidak ditemukan atau tidak dapat diakses.');
        }

        $data['q_id']             = $q_id;
        $data['progress']         = $this->answerModel->getProgress($q_id, $user_data['id']);
        $data['previous_answers'] = $this->answerModel->getUserAnswers($q_id, $user_data['id']);

        return view('alumni/questioner/review', $data);
    }

    /**
     * KEEP: Your enhanced saveAnswer method (this was working correctly)
     */
   public function saveAnswer()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $q_id = $this->request->getPost('q_id');
        $answers = $this->request->getPost('answer') ?? [];
        $files = $this->request->getFiles() ?? [];
        $isLogicallyComplete = $this->request->getPost('is_logically_complete') === '1';
        $userId = session()->get('id');

        log_message('debug', '[saveAnswer] Received request. Q_ID: ' . $q_id . ', User ID: ' . $userId);
        log_message('debug', '[saveAnswer] Is Logically Complete: ' . ($isLogicallyComplete ? 'true' : 'false'));

        if (empty($answers) && empty($files)) {
            log_message('error', '[saveAnswer] No answers or files provided');
            return redirect()->to("/alumni/questionnaires/mulai/$q_id")->with('error', 'Tidak ada jawaban yang disimpan.');
        }

        try {
            $saveSuccess = false;

            // Process answers
            if ($answers) {
                foreach ($answers as $question_id => $answer) {
                    if (empty($answer) && !is_array($answer)) continue;
                    
                    $processedAnswer = is_array($answer) ? json_encode($answer) : $answer;
                    $this->answerModel->saveAnswer($userId, $q_id, $question_id, $processedAnswer);
                    log_message('debug', '[saveAnswer] Saved answer for question ' . $question_id);
                    $saveSuccess = true;
                }
            }

            // Process files
        foreach ($files as $key => $file) {
        log_message('debug', '[saveAnswer] Processing file key: ' . $key . ', Client name: ' . ($file->getClientName() ?? 'N/A'));
            
            if (preg_match('/answer_(\d+)/', $key, $matches)) {
                $question_id = $matches[1];
                log_message('debug', '[saveAnswer] Matched question_id: ' . $question_id);
                
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    log_message('debug', '[saveAnswer] File is valid. Size: ' . $file->getSize() . ' bytes, Extension: ' . $file->getExtension());
                    
                    // FIXED: Path ke public/uploads/answers/ (FCPATH = public root)
                    $uploadPath = FCPATH . 'uploads/answers/';
                    log_message('debug', '[saveAnswer] Target upload path: ' . $uploadPath);
                    
                    // Cek & buat direktori
                    if (!is_dir($uploadPath)) {
                        log_message('info', '[saveAnswer] Creating directory: ' . $uploadPath);
                        if (!mkdir($uploadPath, 0777, true)) {
                            log_message('error', '[saveAnswer] Failed to create directory: ' . $uploadPath . ' (Check permissions!)');
                            throw new \Exception('Gagal membuat folder upload. Periksa permission direktori.');
                        }
                        log_message('info', '[saveAnswer] Directory created successfully.');
                    } else {
                        log_message('debug', '[saveAnswer] Directory already exists.');
                    }
                    
                    // Cek writable
                    if (!is_writable($uploadPath)) {
                        log_message('error', '[saveAnswer] Directory not writable: ' . $uploadPath);
                        throw new \Exception('Folder upload tidak bisa ditulis. Periksa permission.');
                    }
                    
                    $newName = $file->getRandomName();  // e.g., abc123.pdf
                    log_message('debug', '[saveAnswer] Generated new filename: ' . $newName);
                    
                    // Move file
                    if (!$file->move($uploadPath, $newName)) {
                        $error = $file->getErrorString();
                        log_message('error', '[saveAnswer] Move failed for ' . $newName . ': ' . $error);
                        throw new \Exception('Gagal menyimpan file: ' . $error);
                    }
                    
                    // FIXED: Simpan RELATIVE path ke DB (untuk URL: /uploads/answers/...)
                    $relativePath = 'uploaded_file:uploads/answers/' . $newName;
                    $this->answerModel->saveAnswer($userId, $q_id, $question_id, $relativePath);
                    log_message('info', '[saveAnswer] File saved successfully for question ' . $question_id . ' with relative path: ' . $relativePath);
                    $saveSuccess = true;
                } else {
                    $error = $file->getErrorString() ?? 'Unknown error';
                    log_message('error', '[saveAnswer] File invalid or already moved for question ' . $question_id . ': ' . $error);
                }
            } else {
                log_message('warning', '[saveAnswer] File key does not match pattern "answer_{id}": ' . $key);
            }
        }

            // ENHANCED: Set proper completion status and handle announcement
            if ($saveSuccess && $isLogicallyComplete) {
                $this->answerModel->setStatus($q_id, $userId, 'completed');
                log_message('info', '[saveAnswer] Set questionnaire as completed due to logical completion');
                
                // NEW: Check for announcement and redirect accordingly
                $questionnaire = $this->questionnaireModel->find($q_id);
                $announcement = $questionnaire['announcement'] ?? '';
                
                if (!empty(trim($announcement))) {
                    log_message('debug', '[saveAnswer] Announcement found, redirecting to show announcement');
                    // Store announcement data in session for display
                    session()->setFlashdata('show_announcement', true);
                    session()->setFlashdata('announcement_content', $announcement);
                    session()->setFlashdata('questionnaire_title', $questionnaire['title'] ?? 'Kuesioner');
                    
                    return redirect()->to("/alumni/questionnaires/mulai/$q_id?show_announcement=1");
                }
            }

            // Log activity
            try {
                $this->logActivityModel->logAction('submit_questionnaire', 'User ' . $userId . ' submitted questionnaire ID ' . $q_id);
            } catch (\Exception $logException) {
                log_message('warning', '[saveAnswer] Failed to log activity: ' . $logException->getMessage());
            }

            log_message('info', '[saveAnswer] Process completed successfully');
            return redirect()->to("/alumni/questionnaires")->with('success', 'Jawaban berhasil disimpan!');

        } catch (\Exception $e) {
            log_message('error', '[saveAnswer] Error during process: ' . $e->getMessage());
            return redirect()->to("/alumni/questionnaires/mulai/$q_id")->with('error', 'Gagal menyimpan jawaban: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Method untuk menghitung logical progress
     */
    private function calculateLogicalProgressForUser($structure, $previousAnswers)
    {
        if (empty($structure['pages'])) {
            return 0;
        }
        
        $totalRelevantPages = 0;
        $completedRelevantPages = 0;
        
        foreach ($structure['pages'] as $pageIndex => $page) {
            $isPageRelevant = $this->evaluatePageConditionsForUser($page, $previousAnswers);
            
            if ($isPageRelevant) {
                $totalRelevantPages++;
                
                $hasAnswers = $this->pageHasAnswersForUser($page, $previousAnswers);
                if ($hasAnswers) {
                    $completedRelevantPages++;
                }
            }
        }
        
        log_message('debug', '[calculateLogicalProgress] Total relevant: ' . $totalRelevantPages . ', Completed: ' . $completedRelevantPages);
        
        return $totalRelevantPages > 0 ? ($completedRelevantPages / $totalRelevantPages) * 100 : 0;
    }

    /**
     * Helper untuk evaluasi kondisi halaman
     */
   private function evaluatePageConditionsForUser($page, $answers)
    {
        $decoded = json_decode($page['conditional_logic'] ?? '{}', true);
        $conditions = $decoded['conditions'] ?? [];
        $logic_type = $decoded['logic_type'] ?? 'any';

        if (empty($conditions)) {
            return true;
        }

        $pass = ($logic_type === 'all') ? true : false;

        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? '';
            $operator = $condition['operator'] ?? '';
            $value = $condition['value'] ?? '';

            if (!$field || !$operator) continue;

            $userAnswer = $answers['q_' . $field] ?? '';
            $userAnswerArray = is_array(json_decode($userAnswer, true)) ? json_decode($userAnswer, true) : [$userAnswer];

            $match = false;
            switch ($operator) {
                case 'is':
                    $match = in_array($value, $userAnswerArray);
                    break;
                case 'is_not':
                    $match = !in_array($value, $userAnswerArray);
                    break;
                case 'contains':
                    $match = array_filter($userAnswerArray, function($ans) use ($value) {
                        return strpos(strtolower($ans), strtolower($value)) !== false;
                    });
                    $match = !empty($match);
                    break;
                case 'not_contains':
                    $match = array_filter($userAnswerArray, function($ans) use ($value) {
                        return strpos(strtolower($ans), strtolower($value)) === false;
                    });
                    $match = !empty($match);
                    break;
                case 'greater':
                    $match = array_filter($userAnswerArray, function($ans) use ($value) {
                        return is_numeric($ans) && is_numeric($value) && floatval($ans) > floatval($value);
                    });
                    $match = !empty($match);
                    break;
                case 'less':
                    $match = array_filter($userAnswerArray, function($ans) use ($value) {
                        return is_numeric($ans) && is_numeric($value) && floatval($ans) < floatval($value);
                    });
                    $match = !empty($match);
                    break;
            }

            if ($logic_type === 'all') {
                if (!$match) {
                    $pass = false;
                    break;
                }
            } else {
                if ($match) {
                    $pass = true;
                    break;
                }
            }
        }

        return $pass;
    }

    /**
     * Helper untuk cek apakah halaman sudah dijawab
     */
    private function pageHasAnswersForUser($page, $answers)
    {
        foreach ($page['sections'] as $section) {
            foreach ($section['questions'] as $question) {
                $questionAnswer = $answers['q_' . $question['id']] ?? '';
                if (!empty($questionAnswer)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Helper method untuk menghitung total halaman
     */
    private function getTotalPages($questionnaireId)
    {
        $pageModel = new QuestionnairePageModel();
        return $pageModel->where('questionnaire_id', $questionnaireId)->countAllResults();
    }

    public function responseLanding()
    {
        $responseModel = new \App\Models\ResponseModel();

        $yearsRaw = $responseModel->getAvailableYears() ?? [];
        $allYears = array_column($yearsRaw, 'tahun');

        $selectedYear = $this->request->getGet('tahun');
        if (!$selectedYear && !empty($allYears)) {
            $selectedYear = $allYears[0];
        }
        if (!$selectedYear) {
            $selectedYear = date('Y');
        }

        $data = [
            'selectedYear' => $selectedYear,
            'allYears'     => $allYears,
            'data'         => $responseModel->getSummaryByYear($selectedYear)
        ];

        return view('LandingPage/respon', $data);
    }
}