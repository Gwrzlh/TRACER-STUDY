<?php

namespace App\Controllers;

use App\Models\PengaturanDashboardModel;
use App\Controllers\BaseController;

class PengaturanDashboardKaprodi extends BaseController
{
    // Menampilkan halaman pengaturan dashboard kaprodi
    public function index()
    {
        $model = new PengaturanDashboardModel();

        // Ambil data dashboard dengan tipe 'kaprodi'
        $data['dashboard'] = $model->where('tipe', 'kaprodi')->first();

        // Jika belum ada data, buat default agar tidak error di view
        if (!$data['dashboard']) {
            $data['dashboard'] = [
                'id'                  => '',
                'judul'               => '',
                'deskripsi'           => '',
                'judul_kuesioner'     => '',
                'judul_data_alumni'   => '',
                'judul_profil'        => '',
                'judul_ami'           => '',
            ];
        }

        $data['title'] = 'Pengaturan Dashboard Kaprodi';

        return view('adminpage/pengaturan_dashboard/dashboard_kaprodi', $data);
    }

    // Menyimpan hasil perubahan dari form dashboard kaprodi
    public function save()
    {
        $model = new PengaturanDashboardModel();

        $id = $this->request->getPost('id');

        $data = [
            'tipe'                => 'kaprodi',
            'judul'               => $this->request->getPost('judul'),
            'deskripsi'           => $this->request->getPost('deskripsi'),
            'judul_kuesioner'     => $this->request->getPost('judul_kuesioner'),
            'judul_data_alumni'   => $this->request->getPost('judul_data_alumni'),
            'judul_profil'        => $this->request->getPost('judul_profil'),
            'judul_ami'           => $this->request->getPost('judul_ami'),
        ];

        // Update jika ada ID, jika tidak insert baru
        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->to('/pengaturan-dashboard/dashboard-kaprodi')
            ->with('success', 'Teks dashboard Kaprodi berhasil disimpan.');
    }
}
