<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KaprodiController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

   public function dashboard()
{
    if (session('role_id') != 6 || !session()->get('id_account')) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $idProdi = session()->get('id_prodi');

    // 🔹 Ambil data pengaturan dashboard dari tabel
    $dashboardModel = new \App\Models\PengaturanDashboardModel();
    $dashboard = $dashboardModel->where('tipe', 'kaprodi')->first();

    if (!$dashboard) {
        $dashboard = [
            'judul'             => 'Dashboard Kaprodi',
            'deskripsi'         => 'Halo ' . esc(session()->get('username')) . ' (Kaprodi)',
            'judul_kuesioner'   => 'Jumlah Kuesioner Aktif',
            'judul_data_alumni' => 'Jumlah Alumni',
            'judul_profil'      => 'Akreditasi',
            'judul_ami'         => 'AMI',
        ];
    }

    // 🔹 Ambil data prodi kaprodi
    $prodiModel = new \App\Models\Prodi();
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

    // 🔹 Jumlah kuesioner aktif
    $questionnaireModel = new \App\Models\QuestionnairModel();
    $user_data = ['id_prodi' => $idProdi];
    $accessible = $questionnaireModel->getAccessibleQuestionnaires($user_data, 'kaprodi');
    $kuesionerCount = count($accessible);

    // 🔹 Kirim semua ke view
    return view('kaprodi/dashboard', [
        'dashboard'        => $dashboard, // <= ini penting
        'kaprodi'          => $kaprodi,
        'kuesionerCount'   => $kuesionerCount,
        'alumniCount'      => $totalAlumni,
        'alumniMengisi'    => $alumniMengisi,
        'akreditasiAlumni' => $akreditasiAlumni,
        'amiAlumni'        => $amiAlumni,
    ]);
}


   public function alumni()
{
    if (session()->get('role_id') != 6 || !session()->get('id_account')) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $idProdi = session()->get('id_prodi');
    $keyword = $this->request->getGet('keyword');

    $builder = $this->db->table('detailaccount_alumni da')
        ->select('
            da.id,
            da.nama_lengkap,
            da.nim,
            da.angkatan,
            a.email,
            a.username,
            da.notlp,
            j.nama_jurusan,
            p.nama_prodi,
            prov.name AS provinsi,
            c.name AS kota,
            da.tahun_kelulusan,
            da.ipk,
            da.alamat,
            da.alamat2,
            da.jenisKelamin
        ')
        ->join('account a', 'a.id = da.id_account', 'left')
        ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
        ->join('prodi p', 'p.id = da.id_prodi', 'left')
        ->join('provinces prov', 'prov.id = da.id_provinsi', 'left')
        ->join('cities c', 'c.id = da.id_cities', 'left')
        ->where('da.id_prodi', $idProdi);

    // 🔍 FILTER SEARCH
    if (!empty($keyword)) {
        $builder->groupStart()
                ->like('da.nama_lengkap', $keyword)
                ->orLike('a.email', $keyword)
                ->orLike('a.username', $keyword)
                ->orLike('da.nim', $keyword)
                ->orLike('da.angkatan', $keyword)
                ->orLike('da.tahun_kelulusan', $keyword)
                  ->orLike('prov.name', $keyword)   // cari berdasarkan provinsi
            ->orLike('c.name', $keyword)  
                ->groupEnd();
    }

    $alumni = $builder->orderBy('da.angkatan', 'DESC')->get()->getResultArray();

    return view('kaprodi/alumni/index', [
        'alumni' => $alumni,
        'keyword' => $keyword, // untuk isi ulang input
    ]);
}


    public function exportAlumni()
    {
        if (session()->get('role_id') != 6 || !session()->get('id_account')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $idProdi = session()->get('id_prodi');

        // Ambil data alumni
        $alumni = $this->db->table('detailaccount_alumni da')
            ->select('
            da.nama_lengkap,
            da.nim,
            da.angkatan,
            a.email,
            a.username,
            da.notlp,
            j.nama_jurusan,
            p.nama_prodi,
            prov.name AS provinsi,
            c.name AS kota,
            da.tahun_kelulusan,
            da.ipk,
            da.alamat,
            da.alamat2,
            da.jenisKelamin
        ')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->join('provinces prov', 'prov.id = da.id_provinsi', 'left')
            ->join('cities c', 'c.id = da.id_cities', 'left')
            ->where('da.id_prodi', $idProdi)
            ->orderBy('da.angkatan', 'DESC')
            ->get()
            ->getResultArray();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = [
            'No',
            'Nama Lengkap',
            'NIM',
            'Tahun Masuk',
            'Email',
            'Username',
            'No Telepon',
            'Jurusan',
            'Prodi',
            'Provinsi',
            'Kota',
            'Tahun Lulus',
            'IPK',
            'Alamat',
            'Alamat 2',
            'Jenis Kelamin'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Isi data
        $row = 2;
        $no = 1;
        foreach ($alumni as $a) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $a['nama_lengkap']);
            $sheet->setCellValue('C' . $row, $a['nim']);
            $sheet->setCellValue('D' . $row, $a['angkatan']);
            $sheet->setCellValue('E' . $row, $a['email']);
            $sheet->setCellValue('F' . $row, $a['username']);
            $sheet->setCellValue('G' . $row, $a['notlp']);
            $sheet->setCellValue('H' . $row, $a['nama_jurusan']);
            $sheet->setCellValue('I' . $row, $a['nama_prodi']);
            $sheet->setCellValue('J' . $row, $a['provinsi']);
            $sheet->setCellValue('K' . $row, $a['kota']);
            $sheet->setCellValue('L' . $row, $a['tahun_kelulusan']);
            $sheet->setCellValue('M' . $row, $a['ipk']);
            $sheet->setCellValue('N' . $row, $a['alamat']);
            $sheet->setCellValue('O' . $row, $a['alamat2']);
            $sheet->setCellValue('P' . $row, $a['jenisKelamin']);
            $row++;
        }

        // Styling sederhana (header bold)
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Simpan sebagai file Excel
        $filename = 'Data_Alumni_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Output ke browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
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

        $questionnaireModel = new \App\Models\QuestionnairModel();

        // Ambil data kuesioner
        $kuesioner = $questionnaireModel->getAccessibleQuestionnaires($user_data, 'kaprodi');

        // Jika hasil berupa array, urutkan secara manual (yang terbaru di atas)
        if (is_array($kuesioner)) {
            usort($kuesioner, function ($a, $b) {
                // Gunakan kolom created_at kalau ada, kalau tidak gunakan id
                $field = isset($a['created_at']) ? 'created_at' : 'id';
                return strcmp($b[$field], $a[$field]); // urut DESC
            });
        }

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

        $questionnaireModel = new \App\Models\QuestionnairModel();

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

        $questionnaireModel = new \App\Models\QuestionnairModel();
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

    public function akreditasi()
    {
        $db = $this->db;

        $questions = $db->table('questions')
            ->where('is_for_accreditation', 1)
            ->where('created_by_role', 2) // ⬅️ tambahkan ini agar hanya pertanyaan kaprodi
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($questions as $q) {
            $answers = $db->table('answers')
                ->select('answer_text, COUNT(*) as jumlah')
                ->where('question_id', $q['id'])
                ->groupBy('answer_text')
                ->get()
                ->getResultArray();

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

    public function ami()
    {
        $db = $this->db;

        $questions = $db->table('questions')
            ->where('is_for_ami', 1)
            ->where('created_by_role', 2) // ⬅️ tambahkan filter kaprodi
            ->orderBy('created_at', 'ASC')
            ->get()
            ->getResultArray();

        $data = [];

        foreach ($questions as $q) {
            $answers = $db->table('answers')
                ->select('answer_text, COUNT(*) as jumlah')
                ->where('question_id', $q['id'])
                ->groupBy('answer_text')
                ->get()
                ->getResultArray();

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
    // ================== SIMPAN FLAG (KAPRODI) ==================
    public function saveFlags()
    {
        $akreditasi = $this->request->getPost('akreditasi') ?? [];
        $ami        = $this->request->getPost('ami') ?? [];

        $db = $this->db;
        $builder = $db->table('questions');

        // 🔹 Reset dulu semua flag milik kaprodi
        $builder->where('created_by_role', 2)
            ->set(['is_for_accreditation' => 0, 'is_for_ami' => 0])
            ->update();

        // 🔹 Update Akreditasi
        if (!empty($akreditasi)) {
            $builder->whereIn('id', $akreditasi)
                ->set([
                    'is_for_accreditation' => 1,
                    'created_by_role' => 2
                ])
                ->update();
        }

        // 🔹 Update AMI
        if (!empty($ami)) {
            $builder->whereIn('id', $ami)
                ->set([
                    'is_for_ami' => 1,
                    'created_by_role' => 2
                ])
                ->update();
        }

        return redirect()->to(base_url('kaprodi/questioner'))
            ->with('success', 'Data AMI & Akreditasi (Kaprodi) berhasil disimpan.');
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
