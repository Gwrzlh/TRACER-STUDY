<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AtasanController extends Controller
{
  public function dashboard()
{
    // 🔒 Batasi hanya untuk role Atasan
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $pengaturanModel = new \App\Models\PengaturanDashboardModel();

    // 🔹 Ambil pengaturan dashboard untuk tipe "atasan"
    $dashboard = $pengaturanModel->where('tipe', 'atasan')->first();

    // 🔹 Ambil jumlah perusahaan (role_id = 7)
    $totalPerusahaan = (int) $db->table('account')
        ->where('id_role', 7)
        ->countAllResults();

    // 🔹 Ambil 5 alumni terbaru
    $alumni = $db->table('detailaccount_alumni')
        ->select('nama_lengkap, nim, id_jurusan, id_prodi, tahun_kelulusan, ipk, id_cities')
        ->orderBy('id', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    // 🔹 Ambil foto header (jika ada)
    $fotoHeader = $dashboard['foto'] ?? '/images/logo.png';

    // 🔹 Siapkan data yang akan dikirim ke view
    $data = [
        'totalPerusahaan' => $totalPerusahaan,
        'alumni' => $alumni,
        'judul_dashboard' => $dashboard['judul'] ?? 'Dashboard Atasan',
        'deskripsi'       => $dashboard['deskripsi'] ?? 'Halo atasan 👋',
        'judul_kuesioner' => $dashboard['judul_kuesioner' ] ?? 'Total Perusahaan',
        'judul_profil'          => $dashboard[ 'judul_profil' ] ?? 'Grafik Pertumbuhan Alumni',
        'judul_data_alumni'=> $dashboard[ 'judul_data_alumni'] ?? 'Daftar Alumni Terbaru',
        'card_4'          => $dashboard['card_4'] ?? '',
        'card_5'          => $dashboard['card_5'] ?? '',
        'card_6'          => $dashboard['card_6'] ?? '',
        'card_7'          => $dashboard['card_7'] ?? '',
        'fotoHeader'      => $fotoHeader,
    ];

    return view('atasan/dashboard', $data);
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
// 🏢 MENU PERUSAHAAN (SATU PER ATASAN)
// ===============================
public function perusahaan()
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');

    // 🔹 Ambil perusahaan yang terhubung ke atasan (berdasarkan id_perusahaan)
    $perusahaan = $db->table('detailaccount_atasan da')
        ->select('dp.*, provinces.name AS provinsi, cities.name AS kota')
        ->join('detailaccoount_perusahaan dp', 'dp.id = da.id_perusahaan', 'left')
        ->join('provinces', 'provinces.id = dp.id_provinsi', 'left')
        ->join('cities', 'cities.id = dp.id_kota', 'left')
        ->where('da.id_account', $idAtasan)
        ->get()
        ->getRowArray();

    // ✅ Tetap render index, tapi dengan kondisi apakah sudah punya perusahaan atau belum
    return view('atasan/perusahaan/index', [
        'perusahaan' => $perusahaan,
        'message'    => !$perusahaan ? 'Belum ada perusahaan yang terhubung dengan akun Anda.' : null
    ]);
}


public function detailPerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');

    // 🔹 Pastikan perusahaan yang diakses memang milik atasan
   $perusahaan = $db->table('detailaccount_atasan da')
    ->join('detailaccoount_perusahaan dp', 'dp.id = da.id_perusahaan', 'left')
    ->join('provinces p', 'p.id = dp.id_provinsi', 'left')   // join provinsi
    ->join('cities c', 'c.id = dp.id_kota', 'left')          // join kota
    ->where('da.id_account', $idAtasan)
    ->where('dp.id', $id)
    ->select('dp.*, p.name AS provinsi, c.name AS kota')      // ambil nama provinsi & kota
    ->get()
    ->getRowArray();


    if (!$perusahaan) {
        return redirect()->to('/atasan/perusahaan')->with('error', 'Anda tidak memiliki akses ke perusahaan ini.');
    }

    // 🔹 Ambil alumni yang bekerja di perusahaan ini
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

public function editPerusahaan($id)
{
    if (session('role_id') != 8) {
        return redirect()->to('/login')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();
    $idAtasan = session('id_account');

    // 🔹 Pastikan hanya perusahaan milik atasan yang bisa di-edit
    $perusahaan = $db->table('detailaccount_atasan da')
        ->join('detailaccoount_perusahaan dp', 'dp.id = da.id_perusahaan', 'left')
        ->where('da.id_account', $idAtasan)
        ->where('dp.id', $id)
        ->select('dp.*')
        ->get()
        ->getRowArray();

    if (!$perusahaan) {
        return redirect()->to('/atasan/perusahaan')->with('error', 'Perusahaan tidak ditemukan atau bukan milik Anda.');
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

public function getCitiesByProvince($province_id = null)
{
    if (!$province_id || !is_numeric($province_id)) {
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Provinsi tidak valid']);
    }

    try {
        $cityModel = new \App\Models\Cities();

        // ✅ Ambil kota berdasarkan province_id
        $cities = $cityModel
            ->where('province_id', $province_id)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON($cities);
    } catch (\Throwable $e) {
        log_message('error', 'getCitiesByProvince error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Terjadi kesalahan server saat memuat kota.']);
    }
}


}
