<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\QuestionModel;
use App\Models\AnswerModel;
use App\Models\Prodi;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountKaprodi;

class JabatanController extends Controller
{
    public function dashboard()
    {

        // Cek role hanya untuk Jabatan Lainnya
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $questionModel = new QuestionModel();
        $alumniModel   = new DetailaccountAlumni();
        $kaprodiModel  = new DetailaccountKaprodi();
        $prodiModel    = new Prodi();
        $jurusanModel  = new \App\Models\Jurusan();

        // --- Statistik AMI & Akreditasi ---
        $totalPertanyaanAmi = $questionModel->where('is_for_ami', 1)->countAllResults();
        $totalPertanyaanAkreditasi = $questionModel->where('is_for_accreditation', 1)->countAllResults();

        $totalJawabanAmi = $db->table('answers a')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_ami', 1)
            ->where('a.status', 'completed')
            ->countAllResults();

        $totalJawabanAkreditasi = $db->table('answers a')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_accreditation', 1)
            ->where('a.status', 'completed')
            ->countAllResults();

        $grafikAmi = $db->table('answers a')
            ->select('q.question_text, COUNT(a.id) as total')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_ami', 1)
            ->where('a.status', 'completed')
            ->groupBy('q.id')
            ->get()->getResultArray();

        $grafikAkreditasi = $db->table('answers a')
            ->select('q.question_text, COUNT(a.id) as total')
            ->join('questions q', 'q.id = a.question_id')
            ->where('q.is_for_accreditation', 1)
            ->where('a.status', 'completed')
            ->groupBy('q.id')
            ->get()->getResultArray();

        // --- Nested Data Jurusan → Prodi → Alumni ---
        $jurusans = $jurusanModel->findAll();
        $dashboardData = [];

        foreach ($jurusans as $jurusan) {
            $prodis = $prodiModel->where('id_jurusan', $jurusan['id'])->findAll();
            $prodiData = [];

            foreach ($prodis as $prodi) {
                // Ambil alumni per prodi
                $alumni = $alumniModel->db->table('detailaccount_alumni da')
                    ->select('da.*, account.username, jurusan.nama_jurusan, prodi.nama_prodi, provinsi.name as nama_provinsi, cities.name as nama_cities')
                    ->join('account', 'account.id = da.id_account', 'left')
                    ->join('jurusan', 'jurusan.id = da.id_jurusan', 'left')
                    ->join('prodi', 'prodi.id = da.id_prodi', 'left')
                    ->join('provinces provinsi', 'provinsi.id = da.id_provinsi', 'left')
                    ->join('cities', 'cities.id = da.id_cities', 'left')
                    ->where('da.id_prodi', $prodi['id'])
                    ->get()->getResultArray();

                // Ambil kaprodi per prodi
                $kaprodi = $kaprodiModel->db->table('detailaccount_kaprodi dk')
                    ->select('dk.*, account.username, jurusan.nama_jurusan, prodi.nama_prodi')
                    ->join('account', 'account.id = dk.id_account', 'left')
                    ->join('jurusan', 'jurusan.id = dk.id_jurusan', 'left')
                    ->join('prodi', 'prodi.id = dk.id_prodi', 'left')
                    ->where('dk.id_prodi', $prodi['id'])
                    ->get()->getResultArray();

                $prodiData[] = [
                    'prodi' => $prodi,
                    'alumni' => $alumni,
                    'kaprodi' => $kaprodi
                ];
            }

            $dashboardData[] = [
                'jurusan' => $jurusan,
                'prodis'  => $prodiData
            ];
        }

        $data = [
            'totalPertanyaanAmi'        => $totalPertanyaanAmi,
            'totalPertanyaanAkreditasi' => $totalPertanyaanAkreditasi,
            'totalJawabanAmi'           => $totalJawabanAmi,
            'totalJawabanAkreditasi'    => $totalJawabanAkreditasi,
            'grafikAmi'                 => $grafikAmi,
            'grafikAkreditasi'          => $grafikAkreditasi,
            'dashboardData'             => $dashboardData
        ];

        return view('jabatan/dashboard', $data);
    }


    /**
     * Halaman filter AMI / Akreditasi per prodi
     */
    public function controlPanel()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();

        $roles = [
            'kaprodi' => 'Kaprodi',
            'alumni'  => 'Alumni'
        ];

        return view('jabatan/control_panel', [
            'prodiList' => $prodiList,
            'roles'     => $roles,
            'selectedJurusan' => null,
            'selectedProdi'   => null,
            'selectedRole'    => null,
            'dataResult'      => []
        ]);
    }
    public function getProdiByJurusan()
    {
        if (session('role_id') != 9) {
            return $this->response->setJSON(['error' => 'Akses ditolak.']);
        }

        $jurusanId = $this->request->getGet('jurusan_id');
        $prodiModel = new Prodi();

        $prodiList = $prodiModel->where('id_jurusan', $jurusanId)->findAll();

        return $this->response->setJSON($prodiList);
    }

    public function filterControlPanel()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $jurusanId = $this->request->getPost('jurusan_id');
        $prodiId   = $this->request->getPost('prodi_id');
        $role      = $this->request->getPost('role');

        $alumniData = [];
        $kaprodiData = [];

        // === ALUMNI ===
        $alumniModel = new DetailaccountAlumni();
        $builderAlumni = $alumniModel->db->table('detailaccount_alumni da')
            ->select('da.nama_lengkap, da.nim, da.angkatan, da.tahun_kelulusan, da.ipk, da.alamat, da.alamat2, da.jenisKelamin,
                  jurusan.nama_jurusan, prodi.nama_prodi,
                  provinces.name as nama_provinsi, cities.name as nama_cities,
                  account.username')
            ->join('account', 'account.id = da.id_account', 'left')
            ->join('jurusan', 'jurusan.id = da.id_jurusan', 'left')
            ->join('prodi', 'prodi.id = da.id_prodi', 'left')
            ->join('provinces', 'provinces.id = da.id_provinsi', 'left')
            ->join('cities', 'cities.id = da.id_cities', 'left');

        if (!empty($jurusanId) && $jurusanId !== 'all') {
            $builderAlumni->where('da.id_jurusan', $jurusanId);
        }
        if (!empty($prodiId) && $prodiId !== 'all') {
            $builderAlumni->where('da.id_prodi', $prodiId);
        }

        $alumniData = $builderAlumni->get()->getResultArray();

        // === KAPRODI ===
        $kaprodiModel = new DetailaccountKaprodi();
        $builderKaprodi = $kaprodiModel->db->table('detailaccount_kaprodi dk')
            ->select('dk.nama_lengkap, jurusan.nama_jurusan, prodi.nama_prodi, account.username')
            ->join('account', 'account.id = dk.id_account', 'left')
            ->join('jurusan', 'jurusan.id = dk.id_jurusan', 'left')
            ->join('prodi', 'prodi.id = dk.id_prodi', 'left');

        if (!empty($jurusanId) && $jurusanId !== 'all') {
            $builderKaprodi->where('dk.id_jurusan', $jurusanId);
        }
        if (!empty($prodiId) && $prodiId !== 'all') {
            $builderKaprodi->where('dk.id_prodi', $prodiId);
        }

        $kaprodiData = $builderKaprodi->get()->getResultArray();

        // === Dropdown data ===
        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();
        $roles = ['kaprodi' => 'Kaprodi', 'alumni' => 'Alumni'];

        // === Filter sesuai role (kalau role dipilih) ===
        if (!empty($role) && $role !== 'all') {
            if ($role === 'alumni') {
                $kaprodiData = [];
            } elseif ($role === 'kaprodi') {
                $alumniData = [];
            }
        }


        return view('jabatan/control_panel', [
            'prodiList'       => $prodiList,
            'roles'           => $roles,
            'selectedJurusan' => $jurusanId,
            'selectedProdi'   => $prodiId,
            'selectedRole'    => $role,
            'alumniData'      => $alumniData,
            'kaprodiData'     => $kaprodiData,
        ]);
    }




    // ================== DETAIL AMI / AKREDITASI (JABATAN LAINNYA) ==================
    private function loadQuestionsAndAnswers($jenis)
    {
        $questionModel = new QuestionModel();
        $answerModel   = new AnswerModel();

        // Tentukan field AMI atau Akreditasi
        $field = $jenis === 'ami' ? 'is_for_ami' : 'is_for_accreditation';

        // 🔹 Hanya ambil pertanyaan milik Kaprodi (role_id = 2)
        $questions = $questionModel
            ->where($field, 1)
            ->where('created_by_role', 2)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        $selectedQuestion = $this->request->getGet('question_id');
        $answers = [];

        // 🔹 Ambil jawaban jika pertanyaan dipilih
        if ($selectedQuestion) {
            $answers = $answerModel->getAnswersRaw(null, $selectedQuestion);
        }

        return [
            'questions' => $questions,
            'answers' => $answers,
            'selectedQuestion' => $selectedQuestion
        ];
    }

    // ================== DETAIL AMI ==================
    public function detailAmi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();
        $data       = $this->loadQuestionsAndAnswers('ami');

        return view('jabatan/detail_ami', array_merge($data, [
            'prodiList' => $prodiList
        ]));
    }

    // ================== DETAIL AKREDITASI ==================
    public function detailAkreditasi()
    {
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $prodiModel = new Prodi();
        $prodiList  = $prodiModel->getWithJurusan();
        $data       = $this->loadQuestionsAndAnswers('akreditasi');

        return view('jabatan/detail_akreditasi', array_merge($data, [
            'prodiList' => $prodiList
        ]));
    }
}
