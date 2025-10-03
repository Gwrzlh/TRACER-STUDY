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
}
