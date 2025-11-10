<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AtasanController extends Controller
{
    // =========================
    // 🏠 DASHBOARD ATASAN
    // =========================
    public function dashboard()
    {
        if (session('role_id') != 8) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();

        // Total perusahaan (role 7)
        $totalPerusahaan = (int) $db->table('account')->where('id_role', 7)->countAllResults();

        // Data alumni terbaru
        $alumni = $db->table('detailaccount_alumni')
            ->select('nama_lengkap, nim, id_jurusan, id_prodi, tahun_kelulusan, ipk, id_cities')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('atasan/dashboard', [
            'totalPerusahaan' => $totalPerusahaan,
            'alumni' => $alumni
        ]);
    }

    // =========================
    // 📊 KUESIONER (opsional)
    // =========================
    public function kuesionerMulai($id)
    {
        $data['judul'] = "Kuesioner ID: " . $id;
        return view('atasan/kuesioner/form', $data);
    }

    public function kuesionerLanjutkan($id)
    {
        $data['judul'] = "Lanjutkan Kuesioner ID: " . $id;
        return view('atasan/kuesioner/form', $data);
    }

    public function kuesionerLihat($id)
    {
        $data['judul'] = "Lihat Jawaban Kuesioner ID: " . $id;
        return view('atasan/kuesioner/form', $data);
    }

public function tambahAlumni()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');

    // 🔹 Ambil perusahaan milik atasan
    $atasan = $db->table('detailaccount_atasan')
        ->where('id_account', $idAtasan)
        ->get()
        ->getRow();

    if (!$atasan || !$atasan->id_perusahaan) {
        return redirect()->back()->with('error', 'Atasan belum terhubung ke perusahaan.');
    }

    // Ambil input filter dari GET
    $keyword   = strtolower(trim($this->request->getGet('search') ?? ''));
    $tahun     = trim($this->request->getGet('tahun') ?? '');
    $angkatan  = trim($this->request->getGet('angkatan') ?? '');
    $jk        = strtoupper(trim($this->request->getGet('jk') ?? ''));
    $jurusan   = trim($this->request->getGet('jurusan') ?? '');
    $prodi     = trim($this->request->getGet('prodi') ?? '');

    // 🔍 Query utama alumni
    $builder = $db->table('detailaccount_alumni da')
        ->select('
            da.id, da.nama_lengkap, da.nim, da.tahun_kelulusan, da.angkatan, da.ipk,
            da.alamat, da.jenisKelamin, da.notlp,
            a.email,
            j.id AS id_jurusan, j.nama_jurusan,
            p.id AS id_prodi, p.nama_prodi
        ')
        ->join('account a', 'a.id = da.id_account', 'left')
        ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
        ->join('prodi p', 'p.id = da.id_prodi', 'left')
        ->orderBy('da.nama_lengkap', 'ASC');

    // 🔎 Pencarian umum
    if (!empty($keyword)) {
        $builder->groupStart()
            ->like('da.nama_lengkap', $keyword)
            ->orLike('da.nim', $keyword)
            ->orLike('a.email', $keyword)
            ->orLike('da.alamat', $keyword)
            ->orLike('j.nama_jurusan', $keyword)
            ->orLike('p.nama_prodi', $keyword)
            ->orLike('da.angkatan', $keyword)
            ->orLike('da.tahun_kelulusan', $keyword)
        ->groupEnd();
    }

    // 🎓 Filter Tahun Kelulusan
    if (!empty($tahun)) {
        $builder->where('da.tahun_kelulusan', $tahun);
    }

    // 🏫 Filter Angkatan
    if (!empty($angkatan)) {
        $builder->where('da.angkatan', $angkatan);
    }

// 🚻 Filter Jenis Kelamin (fleksibel)
if (!empty($jk)) {
    if ($jk === 'L') {
        $builder->like('LOWER(da.jenisKelamin)', 'laki'); // cocok untuk 'Laki-Laki', 'l', dll
    } elseif ($jk === 'P') {
        $builder->like('LOWER(da.jenisKelamin)', 'p'); // cocok untuk 'P', 'Perempuan'
    }
}



    // 🧑‍🎓 Filter Jurusan
    if (!empty($jurusan)) {
        $builder->where('da.id_jurusan', $jurusan);
    }

    // 🎓 Filter Prodi
    if (!empty($prodi)) {
        $builder->where('da.id_prodi', $prodi);
    }

    $alumni = $builder->get()->getResultArray();

    // Alumni yang sudah terhubung
    $alumniTerkait = $db->table('perusahaan_alumni')
        ->select('id_alumni')
        ->where('id_perusahaan', $atasan->id_perusahaan)
        ->get()
        ->getResultArray();
    $alumniSudah = array_column($alumniTerkait, 'id_alumni');

    // Dropdown data unik
    $tahunList    = $db->table('detailaccount_alumni')->select('tahun_kelulusan')->distinct()->orderBy('tahun_kelulusan', 'DESC')->get()->getResultArray();
    $angkatanList = $db->table('detailaccount_alumni')->select('angkatan')->distinct()->orderBy('angkatan', 'DESC')->get()->getResultArray();
    $jurusanList  = $db->table('jurusan')->select('id, nama_jurusan')->orderBy('nama_jurusan', 'ASC')->get()->getResultArray();
    $prodiList    = $db->table('prodi')->select('id, nama_prodi')->orderBy('nama_prodi', 'ASC')->get()->getResultArray();

    return view('atasan/perusahaan/tambah_alumni', [
        'alumni'        => $alumni,
        'alumniSudah'   => $alumniSudah,
        'keyword'       => $keyword,
        'tahun'         => $tahun,
        'angkatan'      => $angkatan,
        'jk'            => $jk,
        'jurusan'       => $jurusan,
        'prodi'         => $prodi,
        'tahunList'     => $tahunList,
        'angkatanList'  => $angkatanList,
        'jurusanList'   => $jurusanList,
        'prodiList'     => $prodiList,
    ]);
}


public function searchAlumni()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');
    $keyword = trim($this->request->getGet('q') ?? '');

    $atasan = $db->table('detailaccount_atasan')
        ->where('id_account', $idAtasan)
        ->get()
        ->getRow();

    if (!$atasan || !$atasan->id_perusahaan) {
        return $this->response->setJSON([
            'html' => '<tr><td colspan="12" class="text-center text-muted">Atasan belum terhubung ke perusahaan.</td></tr>'
        ]);
    }

    $builder = $db->table('detailaccount_alumni da')
        ->select('
            da.id, da.nama_lengkap, da.nim, da.tahun_kelulusan, da.ipk,
            da.alamat, da.jenisKelamin, da.notlp,
            a.email,
            j.nama_jurusan, p.nama_prodi
        ')
        ->join('account a', 'a.id = da.id_account', 'left')
        ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
        ->join('prodi p', 'p.id = da.id_prodi', 'left')
        ->orderBy('da.nama_lengkap', 'ASC');

    // 🔍 Filter fleksibel: gender atau kata kunci
    $keywordLower = strtolower($keyword);

    if (preg_match('/^l(a|aki)?/i', $keywordLower)) {
        // Semua varian Laki-laki
        $builder->like('LOWER(da.jenisKelamin)', 'laki');
    } elseif (preg_match('/^p(e|erempuan|wanita|cewek)?/i', $keywordLower)) {
        // Semua varian Perempuan
        $builder->like('LOWER(da.jenisKelamin)', 'p');
    } elseif (!empty($keyword)) {
        // Pencarian umum
        $builder->groupStart()
            ->like('LOWER(da.nama_lengkap)', $keywordLower)
            ->orLike('LOWER(da.nim)', $keywordLower)
            ->orLike('LOWER(a.email)', $keywordLower)
            ->orLike('LOWER(da.alamat)', $keywordLower)
            ->orLike('LOWER(j.nama_jurusan)', $keywordLower)
            ->orLike('LOWER(p.nama_prodi)', $keywordLower)
        ->groupEnd();
    }

    $alumni = $builder->get()->getResultArray();

    // Alumni yang sudah terhubung
    $alumniTerkait = $db->table('perusahaan_alumni')
        ->select('id_alumni')
        ->where('id_perusahaan', $atasan->id_perusahaan)
        ->get()
        ->getResultArray();
    $alumniSudah = array_column($alumniTerkait, 'id_alumni');

    // 🔹 Generate HTML
    $html = '';
    if (!empty($alumni)) {
        $no = 1;
        foreach ($alumni as $a) {
            // Normalisasi gender
            $jkText = '-';
            if (stripos($a['jenisKelamin'] ?? '', 'laki') !== false) {
                $jkText = 'Laki-laki';
            } elseif (stripos($a['jenisKelamin'] ?? '', 'p') !== false) {
                $jkText = 'Perempuan';
            }

            $html .= '<tr>';
            $html .= '<td class="text-center">' . $no++ . '</td>';
            $html .= '<td>' . esc($a['nama_lengkap']) . '</td>';
            $html .= '<td>' . esc($a['nim']) . '</td>';
            $html .= '<td>' . esc($a['email'] ?? '-') . '</td>';
            $html .= '<td class="text-center">' . esc($jkText) . '</td>';
            $html .= '<td>' . esc($a['nama_jurusan'] ?? '-') . '</td>';
            $html .= '<td>' . esc($a['nama_prodi'] ?? '-') . '</td>';
            $html .= '<td class="text-center">' . esc($a['tahun_kelulusan'] ?? '-') . '</td>';
            $html .= '<td class="text-center">' . esc($a['ipk'] ?? '-') . '</td>';
            $html .= '<td>' . esc($a['alamat'] ?? '-') . '</td>';
            $html .= '<td>' . esc($a['notlp'] ?? '-') . '</td>';

            if (in_array($a['id'], $alumniSudah)) {
                $html .= '<td class="text-center"><span class="badge bg-success">✔️ Sudah</span></td>';
            } else {
                $html .= '<td class="text-center">
                    <form action="' . base_url('atasan/perusahaan/simpan-alumni/' . $a['id']) . '" method="post" class="d-inline">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm(\'Tambahkan alumni ini ke perusahaan Anda?\')">
                            ➕ Tambah
                        </button>
                    </form>
                </td>';
            }

            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="12" class="text-center text-muted">Tidak ada data alumni ditemukan.</td></tr>';
    }

    return $this->response->setJSON(['html' => $html]);
}



    public function simpanAlumni($idAlumni)
    {
        if (session('role_id') != 8) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $idAtasan = session('id_account');
        $atasan = $db->table('detailaccount_atasan')->where('id_account', $idAtasan)->get()->getRow();

        if (!$atasan || !$atasan->id_perusahaan) {
            return redirect()->back()->with('error', 'Atasan belum terhubung ke perusahaan.');
        }

        // Cek apakah sudah ada
        $cek = $db->table('perusahaan_alumni')
            ->where('id_perusahaan', $atasan->id_perusahaan)
            ->where('id_alumni', $idAlumni)
            ->get()
            ->getRow();

        if ($cek) {
            return redirect()->back()->with('error', 'Alumni sudah terdaftar di perusahaan ini.');
        }

        // Simpan ke tabel relasi
        $db->table('perusahaan_alumni')->insert([
            'id_perusahaan' => $atasan->id_perusahaan,
            'id_alumni' => $idAlumni
        ]);

        return redirect()->to('/atasan/perusahaan/tambah-alumni')->with('success', 'Alumni berhasil ditambahkan.');
    }

    // ======================================
    // 📋 RESPONSE ALUMNI
    // ======================================
public function responseAlumni()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');

    // 🔹 Ambil detail perusahaan milik atasan (sekalian join provinsi & kota)
    $perusahaan = $db->table('detailaccount_atasan da')
        ->select('dp.*, p.name AS provinsi, c.name AS kota')
        ->join('detailaccoount_perusahaan dp', 'dp.id = da.id_perusahaan', 'left')
        ->join('provinces p', 'p.id = dp.id_provinsi', 'left')
        ->join('cities c', 'c.id = dp.id_kota', 'left')
        ->where('da.id_account', $idAtasan)
        ->get()
        ->getRowArray();

    if (!$perusahaan) {
        return redirect()->back()->with('error', 'Atasan belum terhubung ke perusahaan.');
    }

    // 🔹 Ambil daftar alumni dan response kuesioner mereka
    $responses = $db->table('perusahaan_alumni pa')
        ->select('
            a.nama_lengkap,
            r.id AS id_response,
            r.questionnaire_id,
            r.submitted_at,
            r.status
        ')
        ->join('detailaccount_alumni a', 'a.id = pa.id_alumni', 'left')
        ->join('responses r', 'r.account_id = a.id_account', 'left')
        ->where('pa.id_perusahaan', $perusahaan['id'])
        ->orderBy('r.submitted_at', 'DESC')
        ->get()
        ->getResultArray();

    // 🔹 Kirim data ke view
    return view('atasan/perusahaan/response_alumni', [
        'responses' => $responses,
        'perusahaan' => $perusahaan
    ]);
}

    public function Lihatjawaban($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();

    // Ambil data utama response
    $response = $db->table('responses r')
        ->select('r.*, a.nama_lengkap, q.nama_kuesioner')
        ->join('detailaccount_alumni a', 'a.id_account = r.account_id', 'left')
        ->join('questionnaires q', 'q.id = r.questionnaire_id', 'left')
        ->where('r.id', $id)
        ->get()
        ->getRowArray();

    if (!$response) {
        return redirect()->back()->with('error', 'Data jawaban tidak ditemukan.');
    }

    // Ambil isi jawaban (kalau ada tabel jawaban)
    $answers = $db->table('response_answers ra')
        ->select('ra.question_id, qs.pertanyaan, ra.answer_text')
        ->join('questions qs', 'qs.id = ra.question_id', 'left')
        ->where('ra.response_id', $id)
        ->get()
        ->getResultArray();

    return view('atasan/perusahaan/lihatjawaban', [
        'response' => $response,
        'answers'  => $answers
    ]);
}
public function suggestionAlumni()
{
    $keyword = $this->request->getGet('q');
    $db = \Config\Database::connect();

    $query = $db->table('detailaccount_alumni da')
        ->select('da.id, da.nama_lengkap, da.nim')
        ->like('da.nama_lengkap', $keyword)
        ->orLike('da.nim', $keyword)
        ->limit(10)
        ->get()
        ->getResultArray();

    return $this->response->setJSON($query);
}


}
