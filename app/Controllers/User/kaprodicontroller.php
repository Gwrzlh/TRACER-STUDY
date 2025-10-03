<?php

namespace App\Controllers\User;

use CodeIgniter\Controller;
use Config\Database;
use Dompdf\Dompdf;

class KaprodiController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // ================== DASHBOARD ==================
    public function dashboard()
    {
        if (session('role_id') != 6 || !session()->get('id_account')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $idProdi = session()->get('id_prodi');

        // 🔹 Ambil data prodi kaprodi
        $prodiModel = new \App\Models\Organisasi\Prodi();
        $kaprodi = $prodiModel->find($idProdi);

        // 🔹 Jumlah alumni di prodi ini
        $totalAlumni = $this->db->table('detailaccount_alumni')
            ->where('id_prodi', $idProdi)
            ->countAllResults();

        // 🔹 Jumlah alumni yang sudah mengisi kuesioner
        $alumniIsiRow = $this->db->table('answers a')
            ->select('COUNT(DISTINCT a.user_id) as total')
            ->join('detailaccount_alumni al', 'a.user_id = al.id_account', 'left')
            ->where('al.id_prodi', $idProdi)
            ->get()
            ->getRow();
        $alumniMengisi = $alumniIsiRow ? $alumniIsiRow->total : 0;

        // 🔹 Jumlah alumni untuk akreditasi
        $akreditasiRow = $this->db->table('answers a')
            ->select('COUNT(DISTINCT a.user_id) as total')
            ->join('questions q', 'a.question_id = q.id', 'left')
            ->join('detailaccount_alumni al', 'a.user_id = al.id_account', 'left')
            ->where('q.is_for_accreditation', 1)
            ->where('al.id_prodi', $idProdi)
            ->get()
            ->getRow();
        $akreditasiAlumni = $akreditasiRow ? $akreditasiRow->total : 0;

        // 🔹 Jumlah alumni untuk AMI
        $amiRow = $this->db->table('answers a')
            ->select('COUNT(DISTINCT a.user_id) as total')
            ->join('questions q', 'a.question_id = q.id', 'left')
            ->join('detailaccount_alumni al', 'a.user_id = al.id_account', 'left')
            ->where('q.is_for_ami', 1)
            ->where('al.id_prodi', $idProdi)
            ->get()
            ->getRow();
        $amiAlumni = $amiRow ? $amiRow->total : 0;

        // 🔹 Jumlah kuesioner aktif (pakai method bawaan biar sama dengan index)
        $questionnaireModel = new \App\Models\Questionnaire\QuestionnairModel();
        $user_data = ['id_prodi' => $idProdi];
        $accessible = $questionnaireModel->getAccessibleQuestionnaires($user_data, 'kaprodi');
        $kuesionerCount = count($accessible);

        return view('kaprodi/dashboard', [
            'kaprodi'          => $kaprodi,
            'kuesionerCount'   => $kuesionerCount,
            'alumniCount'      => $totalAlumni,
            'alumniMengisi'    => $alumniMengisi,
            'akreditasiAlumni' => $akreditasiAlumni,
            'amiAlumni'        => $amiAlumni,
        ]);
    }





    // // ================== SUPERVISI ==================
    // public function supervisi()
    // {
    //     if (session('role_id') != 6 || !session('id_surveyor')) {
    //         return redirect()->to('/login')->with('error', 'Akses ditolak.');
    //     }

    //     return view('kaprodi/supervisi');
    // }

    // ================== PROFIL ==================
    public function profil()
    {
        $idAccount = session()->get('id_account');

        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, j.nama_jurusan');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $builder->join('jurusan j', 'dk.id_jurusan = j.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        return view('kaprodi/profil/index', ['kaprodi' => $kaprodi]);
    }

    public function editProfil()
    {
        $idAccount = session()->get('id_account');

        // Ambil data Kaprodi
        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, j.nama_jurusan');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $builder->join('jurusan j', 'dk.id_jurusan = j.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        // Ambil list jurusan untuk dropdown
        $jurusanList = $this->db->table('jurusan')->get()->getResultArray();
        $prodiList = $this->db->table('prodi')->get()->getResultArray();


        return view('kaprodi/profil/edit', [
            'kaprodi' => $kaprodi,
            'jurusanList' => $jurusanList,
            'prodiList' => $prodiList
        ]);
    }


    public function updateProfil()
    {
        $idAccount = session()->get('id_account');
        $data = [];

        // Update nama lengkap dan notlp
        $data['nama_lengkap'] = $this->request->getPost('nama_lengkap');
        $data['notlp']        = $this->request->getPost('notlp');

        // Upload file manual
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = time() . '_' . $file->getRandomName();
            $file->move(FCPATH . 'uploads/kaprodi', $newName);
            $data['foto'] = $newName;
            session()->set('foto', $newName);
        }

        // Upload dari kamera (base64)
        $fotoCamera = $this->request->getPost('foto_camera');
        if ($fotoCamera) {
            $fotoData = explode(',', $fotoCamera);
            if (count($fotoData) == 2) {
                $imageData = base64_decode($fotoData[1]);
                $newName = time() . '_camera.png';
                file_put_contents(FCPATH . 'uploads/kaprodi/' . $newName, $imageData);
                $data['foto'] = $newName;
                session()->set('foto', $newName);
            }
        }

        if (!empty($data)) {
            $builder = $this->db->table('detailaccount_kaprodi');
            $builder->where('id_account', $idAccount)->update($data);
        }

        return redirect()->to('/kaprodi/profil')->with('success', 'Profil berhasil diperbarui.');
    }


    // ================== KUESIONER ==================
    public function questioner()
    {
        $idAccount = session()->get('id_account');

        // Ambil data kaprodi + prodi
        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, p.id as id_prodi');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        if (!$kaprodi) {
            return redirect()->to('/login')->with('error', 'Data Kaprodi tidak ditemukan');
        }

        $user_data = [
            'id_prodi' => $kaprodi['id_prodi'],
        ];

        $questionnaireModel = new \App\Models\Questionnaire\QuestionnairModel();
        // khusus kaprodi → filter prodi dan wajib ada conditional_logic
        $kuesioner = $questionnaireModel->getAccessibleQuestionnaires($user_data, 'kaprodi');

        return view('kaprodi/questioner/index', [
            'kuesioner' => $kuesioner,
            'kaprodi'   => $kaprodi
        ]);
    }



    // public function create()
    // {
    //     return view('kaprodi/questioner/create');
    // }
    // public function store()
    // {
    //     $judul = $this->request->getPost('judul');
    //     $status = $this->request->getPost('status');

    //     // Ambil data Kaprodi & prodi dari session
    //     $idAccount = session()->get('id_account');
    //     $builder = $this->db->table('detailaccount_kaprodi dk');
    //     $builder->select('dk.*, p.nama_prodi, p.id as id_prodi');
    //     $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
    //     $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

    //     if (!$kaprodi) {
    //         return redirect()->to('/login')->with('error', 'Data Kaprodi tidak ditemukan.');
    //     }

    //     // Simpan kuesioner baru
    //     $questionnaireModel = new \App\Models\QuestionnairModel();
    //     $questionnaireModel->insert([
    //         'title'             => $judul,
    //         'is_active'         => $status === 'aktif' ? 'active' : 'nonactive',
    //         'conditional_logic' => null, // Bisa ditambahkan nanti
    //         'created_at'        => date('Y-m-d H:i:s'),
    //         'updated_at'        => date('Y-m-d H:i:s'),
    //         'id_prodi'          => $kaprodi['id_prodi'], // otomatis prodi Kaprodi
    //     ]);

    //     // Ambil ID kuesioner terakhir untuk redirect ke halaman page management
    //     $questionnaire_id = $questionnaireModel->getInsertID();

    //     session()->setFlashdata('success', "Kuesioner '$judul' berhasil ditambahkan untuk Prodi '{$kaprodi['nama_prodi']}'.");

    //     // Redirect ke halaman kelola halaman kuesioner
    //     return redirect()->to(base_url("kaprodi/questioner/pages/$questionnaire_id"));
    // }

    // public function pages($questionnaire_id)
    // {
    //     $questionnaireModel = new \App\Models\QuestionnairModel();
    //     $questionnaire = $questionnaireModel->find($questionnaire_id);

    //     if (!$questionnaire) {
    //         return redirect()->to(base_url('kaprodi/questioner'))->with('error', 'Kuesioner tidak ditemukan.');
    //     }

    //     // Ambil data Kaprodi beserta prodi
    //     $idAccount = session()->get('id_account');
    //     $builder = $this->db->table('detailaccount_kaprodi dk');
    //     $builder->select('dk.*, p.id AS prodi_id, p.nama_prodi'); // pastikan alias p.id as prodi_id
    //     $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
    //     $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

    //     if (!$kaprodi || !isset($kaprodi['prodi_id'])) {
    //         return redirect()->to(base_url('kaprodi/questioner'))->with('error', 'Data Prodi Kaprodi tidak ditemukan.');
    //     }

    //     // Cek akses: kuesioner harus milik prodi Kaprodi login
    //     if (!isset($questionnaire['id_prodi']) || $questionnaire['id_prodi'] != $kaprodi['prodi_id']) {
    //         return redirect()->to(base_url('kaprodi/questioner'))->with('error', 'Akses ditolak untuk kuesioner ini.');
    //     }

    //     $pageModel = new \App\Models\QuestionnairePageModel();
    //     $pages = $pageModel->where('questionnaire_id', $questionnaire_id)
    //         ->orderBy('order_no', 'ASC')->findAll();

    //     return view('kaprodi/questioner/pages', [
    //         'questionnaire' => $questionnaire,
    //         'pages'         => $pages
    //     ]);
    // }



    public function pertanyaan($idKuesioner)
    {
        $idAccount = session()->get('id_account');

        // Ambil data kaprodi beserta prodi
        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, p.id as id_prodi');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        if (!$kaprodi) {
            return redirect()->to('/login')->with('error', 'Data Kaprodi tidak ditemukan');
        }

        $user_data = [
            'id_prodi' => $kaprodi['id_prodi'],
        ];

        $questionnaireModel = new \App\Models\Questionnaire\QuestionnairModel();

        // Ambil struktur kuesioner (role 'kaprodi' -> abaikan conditional logic)
        $structure = $questionnaireModel->getQuestionnaireStructure(
            $idKuesioner,
            $user_data,
            [],
            'kaprodi'
        );

        if (empty($structure) || !isset($structure['questionnaire'])) {
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan atau tidak tersedia');
        }

        // Pastikan setiap page punya key 'questions' & 'title'
        $pages = [];
        if (!empty($structure['pages']) && is_array($structure['pages'])) {
            foreach ($structure['pages'] as $page) {
                $page['questions'] = $page['questions'] ?? [];
                $page['title'] = $page['title'] ?? ($page['page_title'] ?? 'Untitled Page');
                $pages[] = $page;
            }
        }

        // Validasi akses: 
        // - Jika kuesioner punya id_prodi → harus sama dengan prodi kaprodi login
        // - Jika id_prodi null → dianggap kuesioner umum (admin), tetap boleh diakses
        $idProdiKuesioner = $structure['questionnaire']['id_prodi'] ?? null;
        if (!empty($idProdiKuesioner) && $idProdiKuesioner != $kaprodi['id_prodi']) {
            return redirect()->to(base_url('kaprodi/questioner'))
                ->with('error', 'Akses ditolak untuk kuesioner ini.');
        }

        return view('kaprodi/questioner/pertanyaan', [
            'idKuesioner'   => $idKuesioner,
            'questionnaire' => $structure['questionnaire'],
            'pages'         => $pages,
            'kaprodi'       => $kaprodi,
        ]);
    }



    public function addToAkreditasi()
    {
        $selected = $this->request->getPost('akreditasi') ?? [];

        if (!empty($selected)) {
            $db = $this->db;
            $builder = $db->table('questions');

            foreach ($selected as $question_id) {
                $builder->where('id', $question_id)->update(['is_for_accreditation' => 1]);
            }

            return redirect()->to(base_url('kaprodi/questioner'))
                ->with('success', 'Pertanyaan (' . implode(', ', $selected) . ') berhasil dipilih untuk Akreditasi.');
        }

        return redirect()->to(base_url('kaprodi/questioner'))
            ->with('error', 'Tidak ada pertanyaan yang dipilih.');
    }

    public function addToAmi()
    {
        $selected = $this->request->getPost('ami') ?? [];

        if (!empty($selected)) {
            $db = $this->db;
            $builder = $db->table('questions');

            foreach ($selected as $question_id) {
                $builder->where('id', $question_id)->update(['is_for_ami' => 1]);
            }

            return redirect()->to(base_url('kaprodi/questioner'))
                ->with('success', 'Pertanyaan (' . implode(', ', $selected) . ') berhasil dipilih untuk AMI.');
        }

        return redirect()->to(base_url('kaprodi/questioner'))
            ->with('error', 'Tidak ada pertanyaan yang dipilih.');
    }



    public function downloadPertanyaan($idKuesioner)
    {
        $idAccount = session()->get('id_account');

        // Ambil data kaprodi beserta prodi
        $builder = $this->db->table('detailaccount_kaprodi dk');
        $builder->select('dk.*, p.nama_prodi, p.id as id_prodi');
        $builder->join('prodi p', 'dk.id_prodi = p.id', 'left');
        $kaprodi = $builder->where('dk.id_account', $idAccount)->get()->getRowArray();

        if (!$kaprodi) {
            return redirect()->to('/login')->with('error', 'Data Kaprodi tidak ditemukan');
        }

        $user_data = [
            'id_prodi' => $kaprodi['id_prodi'],
        ];

        $questionnaireModel = new \App\Models\Questionnaire\QuestionnairModel();
        $structure = $questionnaireModel->getQuestionnaireStructure($idKuesioner, $user_data, [], 'kaprodi');

        if (!$structure) {
            return redirect()->back()->with('error', 'Kuesioner tidak ditemukan atau tidak tersedia');
        }

        $pages = $structure['pages'];

        // render html untuk pdf
        $html = view('kaprodi/questioner/pdf_template', [
            'idKuesioner' => $idKuesioner,
            'pages'       => $pages
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // download
        return $dompdf->stream("pertanyaan_kuesioner_$idKuesioner.pdf", ["Attachment" => true]);
    }

    // ================== AKREDITASI ==================
    public function akreditasi()
    {
        $db = $this->db;

        // Ambil semua pertanyaan akreditasi
        $questions = $db->table('questions')
            ->where('is_for_accreditation', 1)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($questions as $q) {
            // Ambil jawaban per pertanyaan
            $answers = $db->table('answers')
                ->select('answer_text, COUNT(*) as jumlah')
                ->where('question_id', $q['id'])
                ->groupBy('answer_text')
                ->get()
                ->getResultArray();

            // Format jawaban
            $jawaban = [];
            foreach ($answers as $a) {
                $jawaban[] = [
                    'opsi' => $a['answer_text'],
                    'jumlah' => (int) $a['jumlah']
                ];
            }

            $data[] = [
                'id' => $q['id'],
                'teks' => $q['question_text'],
                'jawaban' => $jawaban
            ];
        }

        return view('kaprodi/akreditasi/index', ['pertanyaan' => $data]);
    }




    public function detailAkreditasi($opsi)
    {
        $db = $this->db;

        $alumni = $db->table('answers a')
            ->select('al.nama_lengkap as nama, al.nim, j.nama_jurusan as jurusan, p.nama_prodi as prodi, al.angkatan')
            ->join('detailaccount_alumni al', 'a.user_id = al.id_account')
            ->join('prodi p', 'al.id_prodi = p.id', 'left')
            ->join('jurusan j', 'al.id_jurusan = j.id', 'left')
            ->where('a.answer_text', urldecode($opsi))
            ->get()
            ->getResultArray();

        return view('kaprodi/akreditasi/detail', [
            'opsi' => urldecode($opsi),
            'alumni' => $alumni
        ]);
    }

    // ================== AMI ==================
    public function ami()
    {
        $db = $this->db;

        // Ambil semua pertanyaan AMI
        $questions = $db->table('questions')
            ->where('is_for_ami', 1)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($questions as $q) {
            // Ambil jawaban per pertanyaan
            $answers = $db->table('answers')
                ->select('answer_text, COUNT(*) as jumlah')
                ->where('question_id', $q['id'])
                ->groupBy('answer_text')
                ->get()
                ->getResultArray();

            // Format jawaban
            $jawaban = [];
            foreach ($answers as $a) {
                $jawaban[] = [
                    'opsi' => $a['answer_text'],
                    'jumlah' => (int) $a['jumlah']
                ];
            }

            $data[] = [
                'id' => $q['id'],
                'teks' => $q['question_text'],
                'jawaban' => $jawaban
            ];
        }

        return view('kaprodi/ami/index', ['pertanyaan' => $data]);
    }





    public function detailAmi($opsi)
    {
        $db = $this->db;

        $alumni = $db->table('answers a')
            ->select('al.nama_lengkap as nama, al.nim, j.nama_jurusan as jurusan, p.nama_prodi as prodi, al.angkatan')
            ->join('detailaccount_alumni al', 'a.user_id = al.id_account')
            ->join('prodi p', 'al.id_prodi = p.id', 'left')
            ->join('jurusan j', 'al.id_jurusan = j.id', 'left')
            ->where('a.answer_text', urldecode($opsi))
            ->get()
            ->getResultArray();

        return view('kaprodi/ami/detail', [
            'opsi' => urldecode($opsi),
            'alumni' => $alumni
        ]);
    }
    public function saveFlags()
    {
        $akreditasi = $this->request->getPost('akreditasi') ?? [];
        $ami        = $this->request->getPost('ami') ?? [];

        $db = $this->db;
        $builder = $db->table('questions');

        // 🔹 Update flag Akreditasi
        if (!empty($akreditasi)) {
            $builder->whereIn('id', $akreditasi)->update(['is_for_accreditation' => 1]);
        }

        // 🔹 Update flag AMI
        if (!empty($ami)) {
            $builder->whereIn('id', $ami)->update(['is_for_ami' => 1]);
        }

        // Catatan: pertanyaan lain tetap tidak berubah, jadi pertanyaan lama tetap ada.

        return redirect()->to(base_url('kaprodi/questioner'))
            ->with('success', 'Data berhasil disimpan.');
    }
    public function delete($id)
    {
        $db = $this->db;
        $builder = $db->table('questions');

        // cek apakah pertanyaan ada
        $question = $builder->where('id', $id)->get()->getRowArray();
        if (!$question) {
            return redirect()->back()->with('error', 'Pertanyaan tidak ditemukan.');
        }

        // hapus jawaban terkait dulu
        $db->table('answers')->where('question_id', $id)->delete();

        // hapus pertanyaan
        $builder->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
