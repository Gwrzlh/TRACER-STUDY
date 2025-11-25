<?php
namespace App\Controllers;

use App\Models\QuestionnairModel;
use App\Models\ResponseAtasanModel;
use App\Models\DetailaccountAlumni;
use App\Models\Accounts;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Provincies;
use App\Models\Cities;
use CodeIgniter\I18n\Time;
use App\Models\DetailaccountAtasan;
use Config\Database;
use App\Models\AtasanHelperModel;
use App\Models\AnswerModel;


class AtasanKuesionerController extends BaseController
{
    protected $questionnaireModel;
    protected $answerModel; // ResponseAtasanModel
    protected $detailAccountAlumniModel;
    protected $detailAtasan;
    protected $account;
    protected $Atasanhelper;

    public function __construct()
    {
        $this->questionnaireModel = new QuestionnairModel();
        $this->answerModel = new ResponseAtasanModel();
        $this->detailAccountAlumniModel = new DetailaccountAlumni();
        $this->detailAtasan           = new DetailaccountAtasan();
        $this->account  = new Accounts();
        $this->Atasanhelper = new AtasanHelperModel();
        $this->answerModel = new AnswerModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $atasanId = session()->get('id_account');

        $allQuestionnaires = $this->questionnaireModel
            ->where('is_active', 'active')    // BENAR!
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [];
        $db = \Config\Database::connect();

        foreach ($allQuestionnaires as $q) {
            $json = $q['conditional_logic'] ?? '';

            if (empty($json) || trim($json) === '' || $json === '[]') {
                continue;
            }

            $cond = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($cond)) {
                continue;
            }

            // SUPPORT KEDUA FORMAT: dengan "conditions" atau langsung array
            $conditions = [];
            if (isset($cond['conditions']) && is_array($cond['conditions'])) {
                $conditions = $cond['conditions'];
            } elseif (is_array($cond) && count($cond) > 0 && isset($cond[0]['field'])) {
                $conditions = $cond;  // ← INI UNTUK FORMAT ANDA!
            }

            $bolehTampil = false;
            foreach ($conditions as $c) {
                if (
                    ($c['field'] ?? '') === 'role_id' &&
                    ($c['operator'] ?? '') === 'is' &&
                    ($c['value'] ?? '') == '8'
                ) {
                    $bolehTampil = true;
                    break;
                }
            }

            if (!$bolehTampil) {
                continue;
            }

            // Progress
            $totalAlumni = $db->table('atasan_alumni')
                ->where('id_atasan', $atasanId)
                ->countAllResults();

            $completedCount = $db->table('responses_atasan')
                ->where([
                    'id_questionnaire' => $q['id'],
                    'id_account'       => $atasanId,
                    'status'           => 'completed'
                ])
                ->countAllResults();

            $progress = $totalAlumni > 0 ? round(($completedCount / $totalAlumni) * 100) : 0;

            $data[] = [
                'id'              => $q['id'],
                'judul'           => $q['title'],
                'total_alumni'    => $totalAlumni,
                'completed_count' => $completedCount,
                'progress'        => $progress,
            ];
        }

        return view('atasan/kuesioner/index', ['data' => $data]);
    }

    public function daftarAlumni($q_id)
    {
        $answerModel = new \App\Models\AnswerModel();
        $atasanId = session()->get('id_account');

        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire || $questionnaire['is_active'] !== 'active') {
            return redirect()->to('atasan/kuesioner');
        }

        $detailAtasan = $this->detailAtasan->getDetailAtasanId($atasanId);

        if (!$detailAtasan) {
            $alumni = [];
        } else {
            $alumni = $this->detailAtasan->getAlumniBinaan($detailAtasan['id']);
        }

        return view('atasan/kuesioner/daftar_alumni', [
            'q_id'          => $q_id,
            'alumni'        => $alumni,
            'questionnaire' => $questionnaire,
            'pesan_kosong'  => empty($alumni) ? 'Belum ada alumni binaan.' : null
        ]);
    }
    public function mulai($q_id, $id_alumni_detail)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $atasanAccountId = session()->get('id_account');
        $atasanData      = session()->get();

        // 1. Cek relasi PAKAI MODEL (TANPA $db)
        $relation = $this->Atasanhelper->cekRelasi($atasanAccountId, $id_alumni_detail);

        if (!$relation) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menilai alumni ini.');
        }

        // 2. Ambil detail alumni PAKAI MODEL
        $alumniDetail = $this->Atasanhelper->getAlumniDetail($id_alumni_detail);
        if (!$alumniDetail) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $alumniAccountId = $alumniDetail['id_account'];

        // 3. Ambil profil lengkap alumni
        $alumniProfile = $alumniDetail;
        $alumniProfileDisplay = $alumniDetail;

        // Tambahkan email
        $alumniAccount = $this->account->find($alumniAccountId);
        if ($alumniAccount) {
            $alumniProfile['email'] = $alumniAccount['email'];
            $alumniProfileDisplay['email'] = $alumniAccount['email'];
        }

        // 4. Display name untuk FK
        $jurusanModel = new \App\Models\Jurusan();
        $prodiModel   = new \App\Models\Prodi();
        $provinsiModel = new \App\Models\Provincies();
        $citiesModel   = new \App\Models\Cities();

        if (!empty($alumniProfile['id_jurusan'])) {
            $j = $jurusanModel->find($alumniProfile['id_jurusan']);
            $alumniProfileDisplay['id_jurusan_name'] = $j['nama_jurusan'] ?? '-';
        }
        if (!empty($alumniProfile['id_prodi'])) {
            $p = $prodiModel->find($alumniProfile['id_prodi']);
            $alumniProfileDisplay['id_prodi_name'] = $p['nama_prodi'] ?? '-';
        }
        if (!empty($alumniProfile['id_provinsi'])) {
            $prov = $provinsiModel->find($alumniProfile['id_provinsi']);
            $alumniProfileDisplay['id_provinsi_name'] = $prov['name'] ?? '-';
        }
        if (!empty($alumniProfile['id_cities'])) {
            $city = $citiesModel->find($alumniProfile['id_cities']);
            $alumniProfileDisplay['id_cities_name'] = $city['name'] ?? '-';
        }

        // 5. Options & mapping
        $jurusanOptions = $jurusanModel->findAll();
        $prodiOptions   = $prodiModel->findAll();
        $provinsiOptions = $provinsiModel->findAll();
        $citiesOptions   = $citiesModel->findAll();

        $fieldFriendlyNames = [
            'nama_lengkap' => 'Nama Lengkap', 'nim' => 'NIM', 'id_jurusan' => 'Jurusan',
            'id_prodi' => 'Program Studi', 'angkatan' => 'Angkatan', 'tahun_kelulusan' => 'Tahun Kelulusan',
            'ipk' => 'IPK', 'alamat' => 'Alamat', 'jenisKelamin' => 'Jenis Kelamin',
            'notlp' => 'No. Telepon', 'id_provinsi' => 'Provinsi', 'id_cities' => 'Kota',
            'email' => 'Email',
        ];

        $fieldTypes = [
            'nama_lengkap' => 'text', 'nim' => 'number', 'angkatan' => 'number',
            'tahun_kelulusan' => 'number', 'ipk' => 'decimal', 'notlp' => 'text',
            'email' => 'email', 'id_jurusan' => 'foreign_key:jurusan',
            'id_prodi' => 'foreign_key:prodi', 'id_provinsi' => 'foreign_key:provincies',
            'id_cities' => 'foreign_key:cities',
        ];

        // 6. Kuesioner & jawaban
        $questionnaire = $this->questionnaireModel->find($q_id);
        if (!$questionnaire || $questionnaire['is_active'] !== 'active') {
            return redirect()->back()->with('error', 'Kuesioner tidak aktif.');
        }

       $previousAnswers = $this->answerModel->getAnswers($q_id, $atasanAccountId, $alumniAccountId);
        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $alumniDetail, $previousAnswers, 'atasan');
        $progress = $this->answerModel->calculateProgress($q_id, $atasanAccountId, $structure);
      

        session()->set('current_q_id', $q_id);
        session()->set('current_alumni_detail_id', $id_alumni_detail);

    //   // HITUNG HALAMAN TERAKHIR YANG ADA JAWABANNYA
    //     $lastAnsweredPage = 0; // default halaman pertama (index 0)

    //     if (!empty($oldAnswers)) {
    //         foreach ($structure['pages'] as $pageIndex => $page) {
    //             $pageHasAnswer = false;

    //             foreach ($page['sections'] as $section) {
    //                 foreach ($section['questions'] as $question) {
    //                     $qid = $question['id'];
    //                     if (isset($oldAnswers[$qid]) && $oldAnswers[$qid] !== '' && $oldAnswers[$qid] !== null) {
    //                         $pageHasAnswer = true;
    //                         break 3;
    //                     }
    //                 }
    //             }

    //             if ($pageHasAnswer) {
    //                 $lastAnsweredPage = $pageIndex; // simpan index halaman yang punya jawaban
    //             } else {
    //                 break; // halaman berikutnya belum diisi
    //             }
    //         }
    //     }
        return view('atasan/kuesioner/fill', [
            'q_id'                  => $q_id,
            'id_alumni_detail'      => $id_alumni_detail,
            'id_alumni_account'     => $alumniAccountId,
            'structure'             => $structure,
            'previous_answers'      => $previousAnswers,
            'progress'              => $progress,
            'alumni_profile'        => $alumniProfile,
            'alumni_profile_display'=> $alumniProfileDisplay,
            'field_friendly_names'  => $fieldFriendlyNames,
            'field_types'           => $fieldTypes,
            'jurusan_options'       => $jurusanOptions,
            'detail_alumni'         => $alumniDetail,
            'prodi_options'         => $prodiOptions,
            'provinsi_options'      => $provinsiOptions,
            'cities_options'        => $citiesOptions,
            // 'oldAnswers'         => $oldAnswers,
            // 'lastAnsweredPage'   => $lastAnsweredPage + 1,
            'questionnaire_title'   => $questionnaire['title'] ?? 'Penilaian Atasan',

            'isRequired'   => false,
            'isReadonly'   => false,
            'isAtasanMode' => true,
        ]);
    }
    public function saveAnswer()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $q_id = $this->request->getPost('q_id');
        $id_alumni_account = $this->request->getPost('id_alumni_account'); // tambahkan ini
        $answers = $this->request->getPost('answer') ?? [];
        $isLogicallyComplete = $this->request->getPost('is_logically_complete') === '1';
        $atasanId = session()->get('id');
        
        if (empty($q_id) || empty($id_alumni_account)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data alumni atau kuesioner tidak valid'
                ]);
            }
        $saveSuccess = false;

        // Simpan jawaban
        foreach ($answers as $question_id => $answer) {
            if ($answer === '' || $answer === null) continue;

            $value = is_array($answer) ? json_encode($answer) : $answer;
            $this->answerModel->saveAnswer(
                        $atasanId,
                        $q_id,
                        $question_id,
                        $value,
                        $id_alumni_account  
                    );
                $saveSuccess = true;
        }

                // Jika ini submit akhir
           if ($saveSuccess && $isLogicallyComplete) {
                // UPDATE STATUS DI responses_atasan JADI completed
                $responseAtasanModel = new \App\Models\ResponseAtasanModel();
                $responseAtasanModel->where([
                    'id_questionnaire' => $q_id,
                    'id_account'       => $atasanId,
                    'id_alumni'        => $id_alumni_account
                ])->set(['status' => 'completed'])->update();

                // Juga update semua jawaban di tabel answers jadi completed
                $this->answerModel->where([
                    'questionnaire_id' => $q_id,
                    'user_id'          => $atasanId,
                    'alumni_id'       => $id_alumni_account
                ])->set(['STATUS' => 'completed'])->update();

                return $this->response->setJSON([
                    'success' => true,
                    'completed' => true,
                    'message' => 'Penilaian berhasil diselesaikan!',
                    'redirect' => base_url("atasan/kuesioner/daftar-alumni/{$q_id}")
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'completed' => false,
                'message' => 'Draft tersimpan otomatis'
            ]);
    }

    public function lihat($q_id)
    {
        $userId = session()->get('id_account');
        $alumniId = $this->request->getGet('id_alumni');

        // Validate relation
        $db = \Config\Database::connect();
        $relation = $db->table('atasan_alumni')->where([
            'id_atasan' => $userId,
            'id_alumni' => $alumniId
        ])->get()->getRow();
        if (!$relation) {
            return redirect()->back()->with('error', 'Invalid alumni access');
        }

        // Fetch alumni profile & display
        $alumniProfile = $this->detailAccountAlumniModel->where('id_account', $alumniId)->first() ?? [];
        $alumniProfileDisplay = []; // Same as in mulai()...

        // Merge for structure
        $userData = session()->get();
        $mergedUserData = array_merge($userData, $alumniProfile);

        $previousAnswers = $this->answerModel->getAnswers($q_id, $userId, $alumniId);
        $structure = $this->questionnaireModel->getQuestionnaireStructure($q_id, $mergedUserData, $previousAnswers, 'atasan');

        // Field friendly names & types (same as mulai)

        return view('atasan/kuesioner/review', [
            'structure' => $structure,
            'previous_answers' => $previousAnswers,
            'user_profile' => $alumniProfile,
            'user_profile_display' => $alumniProfileDisplay,
            // Add field_friendly_names, etc.
        ]);
    }
    public function lanjutkan($q_id,$id_alumni_detail)
    {
        return $this->mulai($q_id,$id_alumni_detail);
    }
}