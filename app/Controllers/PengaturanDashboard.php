<?php

namespace App\Controllers;

use App\Models\PengaturanDashboardModel;
use App\Controllers\BaseController;

class PengaturanDashboard extends BaseController
{
    // Halaman pengaturan dashboard alumni
    public function dashboardAlumni()
    {
        $model = new PengaturanDashboardModel();
        $data['dashboard'] = $model->first(); // Ambil data pertama dari tabel
        $data['title'] = 'Pengaturan Dashboard Alumni';

        return view('adminpage/pengaturan_dashboard/dashboard_alumni', $data);
    }

    // Simpan hasil edit teks dashboard alumni
    public function saveDashboardAlumni()
    {
        $model = new PengaturanDashboardModel();

        $id = $this->request->getPost('id');

        $data = [
            'judul'                => $this->request->getPost('judul'),
            'deskripsi'            => $this->request->getPost('deskripsi'),
            'judul_profil'         => $this->request->getPost('judul_profil'),
            'deskripsi_profil'     => $this->request->getPost('deskripsi_profil'),
            'judul_kuesioner'      => $this->request->getPost('judul_kuesioner'),
            'deskripsi_kuesioner'  => $this->request->getPost('deskripsi_kuesioner'),
        ];

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->insert($data);
        }

        return redirect()->to('/pengaturan-dashboard/dashboard-alumni')
            ->with('success', 'Isi teks dashboard alumni berhasil disimpan.');
    }
}
