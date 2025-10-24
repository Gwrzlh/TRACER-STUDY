<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AtasanController extends Controller
{
    public function dashboard()
    {
        // Hanya atasan yang boleh masuk
        if (session('role_id') != 8) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();

        // Ambil jumlah perusahaan dari tabel `account` yang berstatus role perusahaan (id_role = 7)
        $totalPerusahaan = (int) $db->table('account')->where('id_role', 7)->countAllResults();

        // Ambil data alumni terbaru dari detailaccount_alumni
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
    // Tambahan untuk Kuesioner
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
// ===============================
// 📦 MENU PERUSAHAAN UNTUK ATASAN
// ===============================
public function perusahaan()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $perusahaanModel = new \App\Models\DetailaccountPerusahaan();
    $data['perusahaan'] = $perusahaanModel
        ->select('detailaccoount_perusahaan.*, provinces.name as provinsi, cities.name as kota')
        ->join('provinces', 'provinces.id = detailaccoount_perusahaan.id_provinsi', 'left')
        ->join('cities', 'cities.id = detailaccoount_perusahaan.id_kota', 'left')
        ->orderBy('detailaccoount_perusahaan.nama_perusahaan', 'ASC')
        ->findAll();

    return view('atasan/perusahaan/index', $data);
}

// ===============================
// 📄 DETAIL PERUSAHAAN + ALUMNI
// ===============================
public function detailPerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();

    // Ambil data perusahaan
    $perusahaan = $db->table('detailaccoount_perusahaan')
        ->where('id', $id)
        ->get()
        ->getRowArray();

    if (!$perusahaan) {
        return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
    }

    // Ambil data alumni yang bekerja di perusahaan ini
    $alumni = $db->table('riwayat_pekerjaan')
        ->select('
            detailaccount_alumni.nama_lengkap, 
            riwayat_pekerjaan.jabatan, 
            riwayat_pekerjaan.tahun_masuk, 
            riwayat_pekerjaan.tahun_keluar, 
            riwayat_pekerjaan.masih
        ')
        ->join('detailaccount_alumni', 'detailaccount_alumni.id_account = riwayat_pekerjaan.id_alumni', 'left')
        ->where('riwayat_pekerjaan.perusahaan', $perusahaan['nama_perusahaan'])
        ->get()
        ->getResultArray();

    return view('atasan/perusahaan/detail', [
        'perusahaan' => $perusahaan,
        'alumni'     => $alumni
    ]);
}

// ===============================
// ✏️ EDIT & UPDATE PERUSAHAAN
// ===============================
public function editPerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $model = new \App\Models\DetailaccountPerusahaan();
    $perusahaan = $model->find($id);

    if (!$perusahaan) {
        return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
    }

    $provinsiModel = new \App\Models\Provincies();
    $kotaModel = new \App\Models\Cities();

    $data = [
        'perusahaan' => $perusahaan,
        'provinces'  => $provinsiModel->orderBy('name', 'ASC')->findAll(),
        'cities'     => $kotaModel->where('province_id', $perusahaan['id_provinsi'])->orderBy('name', 'ASC')->findAll()
    ];

    return view('atasan/perusahaan/edit', $data);
}

public function updatePerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $model = new \App\Models\DetailaccountPerusahaan();

    $data = [
        'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
        'alamat1'         => $this->request->getPost('alamat1'),
        'alamat2'         => $this->request->getPost('alamat2'),
        'id_provinsi'     => $this->request->getPost('id_provinsi'),
        'id_kota'         => $this->request->getPost('id_kota'),
        'kodepos'         => $this->request->getPost('kodepos'),
        'noTlp'           => $this->request->getPost('noTlp'),
    ];

    $model->update($id, $data);

    return redirect()->to('/atasan/perusahaan')->with('success', 'Data perusahaan berhasil diperbarui.');
}

// ===============================
// ➕ TAMBAH PERUSAHAAN
// ===============================
public function tambahPerusahaan()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $provinsiModel = new \App\Models\Provincies();
    $data['provinces'] = $provinsiModel->orderBy('name', 'ASC')->findAll();
    $data['cities'] = [];

    return view('atasan/perusahaan/create', $data);
}

public function simpanPerusahaan()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $model = new \App\Models\DetailaccountPerusahaan();

    $data = [
        'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
        'alamat1'         => $this->request->getPost('alamat1'),
        'alamat2'         => $this->request->getPost('alamat2'),
        'id_provinsi'     => $this->request->getPost('id_provinsi'),
        'id_kota'         => $this->request->getPost('id_kota'),
        'kodepos'         => $this->request->getPost('kodepos'),
        'noTlp'           => $this->request->getPost('noTlp'),
    ];

    $model->insert($data);

    return redirect()->to('/atasan/perusahaan')->with('success', 'Perusahaan baru berhasil ditambahkan.');
}

// ===============================
// 🗑️ HAPUS PERUSAHAAN
// ===============================
public function hapusPerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $model = new \App\Models\DetailaccountPerusahaan();

    // 🔹 Cek apakah perusahaan ada
    $perusahaan = $model->find($id);
    if (!$perusahaan) {
        return redirect()->to('/atasan/perusahaan')->with('error', 'Perusahaan tidak ditemukan.');
    }

    // 🔹 Cek apakah masih ada alumni aktif di perusahaan ini
    $alumniAktif = $db->table('riwayat_pekerjaan')
        ->where('id_perusahaan', $id)
        ->where('is_current', 1)
        ->countAllResults();

    if ($alumniAktif > 0) {
        return redirect()->to('/atasan/perusahaan')
            ->with('error', 'Tidak bisa menghapus perusahaan karena masih ada alumni yang bekerja di sana.');
    }

    // 🔹 Jika aman, hapus perusahaan
    if ($model->delete($id)) {
        return redirect()->to('/atasan/perusahaan')->with('success', 'Perusahaan berhasil dihapus.');
    }

    return redirect()->to('/atasan/perusahaan')->with('error', 'Gagal menghapus perusahaan.');
}


// ===============================
// 🌆 GET KOTA BERDASARKAN PROVINSI (AJAX)
// ===============================
public function getCitiesByProvince($province_id = null)
{
    // validasi dasar
    if (!$province_id || !is_numeric($province_id)) {
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Provinsi tidak valid']);
    }

    try {
        $cityModel = new \App\Models\Cities();

        // 1) coba kolom umum 'province_id'
        $cities = $cityModel->where('province_id', $province_id)->orderBy('name', 'ASC')->findAll();

        // 2) kalau kosong, coba alternatif 'id_provinsi' (beberapa DB pakai nama ini)
        if (empty($cities)) {
            $cities = $cityModel->where('id_provinsi', $province_id)->orderBy('name', 'ASC')->findAll();
        }

        // 3) jika masih kosong, balikin pesan (bukan error http) tapi array kosong
        return $this->response->setJSON($cities);
    } catch (\Throwable $e) {
        // log error supaya bisa dilihat di writable/logs
        log_message('error', 'getCitiesByProvince error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Terjadi kesalahan server saat memuat kota.']);
    }
}



}
