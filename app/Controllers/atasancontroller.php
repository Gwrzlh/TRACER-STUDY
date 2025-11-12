<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PesanModel;

class AtasanController extends Controller
{
      protected $pesanModel;
   public function __construct()
    {
         $this->pesanModel = new PesanModel();
    }
    // =========================
    // 🏠 DASHBOARD ATASAN
    // =========================
public function dashboard()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $pengaturanModel = new \App\Models\PengaturanDashboardModel();

    // 🔹 Ambil pengaturan dashboard untuk tipe "atasan"
    $dashboard = $pengaturanModel->where('tipe', 'atasan')->first();

    // 🔹 Cari ID atasan berdasarkan account login
    $atasan = $db->table('detailaccount_atasan')
        ->select('id')
        ->where('id_account', session('id_account'))
        ->get()
        ->getRow();

    if (!$atasan) {
        return redirect()->to('/login')->with('error', 'Data atasan tidak ditemukan.');
    }

    // 🔹 Ambil jumlah perusahaan (role_id = 7)
    $totalPerusahaan = (int) $db->table('account')
        ->where('id_role', 7)
        ->countAllResults();

    // 🔹 Ambil alumni yang sudah direlasikan ke atasan ini
    $alumni = $db->table('atasan_alumni aa')
        ->select('
            da.nama_lengkap,
            da.nim,
            j.nama_jurusan,
            p.nama_prodi,
            da.tahun_kelulusan,
            da.ipk,
            c.name AS kota
        ')
        ->join('detailaccount_alumni da', 'da.id = aa.id_alumni', 'left')
        ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
        ->join('prodi p', 'p.id = da.id_prodi', 'left')
        ->join('cities c', 'c.id = da.id_cities', 'left')
        ->where('aa.id_atasan', $atasan->id)
        ->orderBy('da.id', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    // 🔹 Data untuk grafik (jumlah alumni per tahun kelulusan)
    $chartData = $db->table('atasan_alumni aa')
        ->select('da.tahun_kelulusan, COUNT(da.id) AS total')
        ->join('detailaccount_alumni da', 'da.id = aa.id_alumni', 'left')
        ->where('aa.id_atasan', $atasan->id)
        ->groupBy('da.tahun_kelulusan')
        ->orderBy('da.tahun_kelulusan', 'ASC')
        ->get()
        ->getResultArray();

    // 🔹 Ambil data penilaian alumni (pakai sistem bintang baru)
    $penilaian = $db->table('penilaian_alumni pa')
        ->select('
            da.nama_lengkap,
            da.nim,
            p.nama_prodi,
            pa.kelengkapan,
            pa.kejelasan,
            pa.konsistensi,
            pa.refleksi,
            pa.catatan,
            pa.created_at,
            ROUND((pa.kelengkapan + pa.kejelasan + pa.konsistensi + pa.refleksi) / 4, 1) AS rata_rata
        ')
        ->join('detailaccount_alumni da', 'da.id = pa.id_alumni', 'left')
        ->join('prodi p', 'p.id = da.id_prodi', 'left')
        ->where('pa.id_atasan', $atasan->id)
        ->orderBy('pa.created_at', 'DESC')
        ->get()
        ->getResultArray();

    // 🔹 Siapkan data ke view
    $data = [
        'totalPerusahaan'   => $totalPerusahaan,
        'alumni'            => $alumni,
        'chartData'         => $chartData,
        'penilaian'         => $penilaian,
        'judul_dashboard'   => $dashboard['judul'] ?? 'Dashboard Atasan',
        'deskripsi'         => $dashboard['deskripsi'] ?? 'Halo atasan 👋',
        'judul_kuesioner'   => $dashboard['judul_kuesioner'] ?? 'Total Perusahaan',
        'judul_profil'      => $dashboard['judul_profil'] ?? 'Grafik Pertumbuhan Alumni',
        'judul_data_alumni' => $dashboard['judul_data_alumni'] ?? 'Daftar Alumni Anda',
        'fotoHeader'        => $dashboard['foto'] ?? '/images/logo.png',
    ];

    return view('atasan/dashboard', $data);
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

// ======================================
// 👥 MENU ALUMNI - LIHAT DAN NILAI
// ======================================
public function alumni()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAccount = session('id_account');

    // Ambil data atasan
    $atasan = $db->table('detailaccount_atasan')
        ->where('id_account', $idAccount)
        ->get()
        ->getRow();

    if (!$atasan) {
        return redirect()->back()->with('error', 'Data atasan tidak ditemukan.');
    }

    // Ambil data alumni + status kuesioner + status penilaian
    $alumni = $db->table('atasan_alumni aa')
        ->select("
            al.id, 
            al.nama_lengkap, 
            al.nim, 
            al.tahun_kelulusan, 
            al.ipk,
            p.nama_prodi,

            -- Hitung jumlah kuesioner yang diselesaikan
            (
                SELECT COUNT(r.id)
                FROM responses r
                JOIN account acc2 ON acc2.id = r.account_id
                WHERE acc2.id = al.id_account
                AND r.status = 'completed'
            ) AS total_responses,

            -- Data penilaian (bisa null)
            pa.id AS id_penilaian,
            pa.kelengkapan, 
            pa.kejelasan, 
            pa.konsistensi, 
            pa.refleksi, 
            pa.catatan,

            -- Rata-rata jika ada data
            CASE 
                WHEN pa.id IS NOT NULL THEN 
                    ROUND((pa.kelengkapan + pa.kejelasan + pa.konsistensi + pa.refleksi)/4,1)
                ELSE NULL
            END AS rata_rata
        ")
        ->join('detailaccount_alumni al', 'al.id = aa.id_alumni', 'left')
        ->join('prodi p', 'p.id = al.id_prodi', 'left')
        ->join('penilaian_alumni pa', 'pa.id_alumni = al.id AND pa.id_atasan = aa.id_atasan', 'left')
        ->where('aa.id_atasan', $atasan->id)
        ->orderBy('al.nama_lengkap', 'ASC')
        ->get()
        ->getResultArray();

    // Tambahkan status logika tambahan
    foreach ($alumni as &$a) {
        $a['boleh_dinilai'] = ($a['total_responses'] > 0);       // hanya bisa dinilai jika sudah isi kuesioner
        $a['sudah_dinilai'] = !empty($a['id_penilaian']);        // hanya true jika ada data di penilaian_alumni
    }

    return view('atasan/alumni/index', ['alumni' => $alumni]);
}



// ======================================
// 💬 SIMPAN PENILAIAN ALUMNI (BINTANG)
// ======================================
public function simpanPenilaian($idAlumni)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAccountAtasan = session('id_account');

    // ===============================
    // 🧑‍💼 Ambil data atasan
    // ===============================
    $atasan = $db->table('detailaccount_atasan')
        ->where('id_account', $idAccountAtasan)
        ->get()
        ->getRow();

    if (!$atasan) {
        return redirect()->back()->with('error', 'Data atasan tidak ditemukan.');
    }

    // ===============================
    // 📋 Pastikan alumni sudah isi kuesioner minimal 1
    // ===============================
    $completed = $db->table('responses r')
        ->join('account acc', 'acc.id = r.account_id')
        ->join('detailaccount_alumni da', 'da.id_account = acc.id')
        ->where('da.id', $idAlumni)
        ->where('r.status', 'completed')
        ->countAllResults();

    if ($completed == 0) {
        return redirect()->back()->with('error', 'Alumni belum menyelesaikan kuesioner, tidak dapat dinilai.');
    }

    // ===============================
    // 📝 Ambil data input penilaian
    // ===============================
    $data = [
        'id_atasan'   => $atasan->id,
        'id_alumni'   => $idAlumni,
        'kelengkapan' => $this->request->getPost('kelengkapan'),
        'kejelasan'   => $this->request->getPost('kejelasan'),
        'konsistensi' => $this->request->getPost('konsistensi'),
        'refleksi'    => $this->request->getPost('refleksi'),
        'catatan'     => $this->request->getPost('catatan'),
        'created_at'  => date('Y-m-d H:i:s'),
    ];

    // Hitung rata-rata penilaian
    $rataRata = round((
        ($data['kelengkapan'] + $data['kejelasan'] + $data['konsistensi'] + $data['refleksi']) / 4
    ), 1);

    // ===============================
    // 💾 Simpan atau update penilaian
    // ===============================
    $cek = $db->table('penilaian_alumni')
        ->where('id_atasan', $atasan->id)
        ->where('id_alumni', $idAlumni)
        ->get()
        ->getRow();

    if ($cek) {
        $db->table('penilaian_alumni')->where('id', $cek->id)->update($data);
        $pesanFlash = 'Penilaian berhasil diperbarui.';
    } else {
        $db->table('penilaian_alumni')->insert($data);
        $pesanFlash = 'Penilaian berhasil disimpan.';
    }

    // ===============================
    // ✉️ Kirim notifikasi otomatis ke alumni
    // ===============================
    $alumniAcc = $db->table('detailaccount_alumni')
        ->select('id_account, nama_lengkap')
        ->where('id', $idAlumni)
        ->get()
        ->getRow();

    if ($alumniAcc && $alumniAcc->id_account) {
        // Buat isi pesan otomatis
        $isiPesan = "Atasan Anda telah memberikan penilaian terhadap kuesioner Anda.\n\n" .
                    "⭐ Nilai rata-rata: {$rataRata}/5\n" .
                    "🗒️ Catatan: " . ($data['catatan'] ?: 'Tidak ada catatan tambahan.');

        // Insert ke tabel pesan (tanpa kolom 'judul' atau 'isi')
        $db->table('pesan')->insert([
            'id_pengirim' => $idAccountAtasan,
            'id_penerima' => $alumniAcc->id_account,
            'pesan'       => $isiPesan,
            'status'      => 'terkirim',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    return redirect()->back()->with('success', $pesanFlash);
}




    // ======================================
    // 📊 REKAP PENILAIAN
    // ======================================
    public function rekapPenilaian()
    {
        if (session('role_id') != 8) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $idAtasan = session('id_account');

        $atasan = $db->table('detailaccount_atasan')
            ->where('id_account', $idAtasan)
            ->get()
            ->getRow();

        if (!$atasan) {
            return redirect()->back()->with('error', 'Data atasan tidak ditemukan.');
        }

        $penilaian = $db->table('penilaian_alumni pa')
            ->select("
                da.nama_lengkap, da.nim, pr.nama_prodi,
                pa.kelengkapan, pa.kejelasan, pa.konsistensi, pa.refleksi, pa.catatan, pa.created_at,
                ROUND((pa.kelengkapan + pa.kejelasan + pa.konsistensi + pa.refleksi)/4,1) AS rata_rata
            ")
            ->join('detailaccount_alumni da', 'da.id = pa.id_alumni', 'left')
            ->join('prodi pr', 'pr.id = da.id_prodi', 'left')
            ->where('pa.id_atasan', $atasan->id)
            ->orderBy('pa.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return view('atasan/dashboard', ['penilaian' => $penilaian]);
    }
// =============================
// 🔔 NOTIFIKASI UNTUK ATASAN
// =============================

public function notifikasi()
{
    $idAtasan = session()->get('id');
    $pesan = $this->pesanModel
        ->select('pesan.*, COALESCE(account.username, "Admin") as nama_pengirim')
        ->join('account', 'account.id = pesan.id_pengirim', 'left')
        ->where('id_penerima', $idAtasan)
        ->orderBy('pesan.created_at', 'DESC')
        ->findAll();

    return view('atasan/notifikasi', ['pesan' => $pesan]);
}

public function viewPesan($idPesan)
{
    $pesan = $this->pesanModel
        ->select('pesan.*, COALESCE(account.username, "Admin") as nama_pengirim')
        ->join('account', 'account.id = pesan.id_pengirim', 'left')
        ->where('pesan.id_pesan', $idPesan)
        ->first();

    if (!$pesan) {
        return redirect()->to('/atasan/notifikasi')->with('error', 'Pesan tidak ditemukan.');
    }

    // ubah status jadi dibaca
    $this->pesanModel->update($idPesan, ['status' => 'dibaca']);

    return view('atasan/viewpesan', ['pesan' => $pesan]);
}

public function getNotifCount()
{
    $idAtasan = session()->get('id');
    $pesan = $this->pesanModel
        ->where('id_penerima', $idAtasan)
        ->where('status', 'terkirim')
        ->findAll();

    return $this->response->setJSON(['jumlah' => count($pesan)]);
}

public function hapusNotifikasi($id)
{
    $pesan = $this->pesanModel->find($id);

    if ($pesan && $pesan['id_penerima'] == session()->get('id')) {
        $this->pesanModel->delete($id);
        return redirect()->to('/atasan/notifikasi')->with('success', 'Pesan berhasil dihapus.');
    }

    return redirect()->to('/atasan/notifikasi')->with('error', 'Pesan tidak ditemukan atau bukan milik Anda.');
}
 public function tandaiDibaca($id_pesan)
    {
        $this->pesanModel->update($id_pesan, ['status' => 'dibaca']);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

}