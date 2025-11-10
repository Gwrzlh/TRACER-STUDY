<?php

namespace App\Controllers;

use App\Models\PengaturanDashboardModel;
use CodeIgniter\Controller;

class PengaturanDashboardJabatanLainnya extends BaseController
{
    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new PengaturanDashboardModel();
    }

    public function index()
    {
        // Ambil data dashboard jabatan lainnya
        $dashboard = $this->dashboardModel
            ->where('tipe', 'jabatan_lainnya')
            ->first();

        return view('adminpage/pengaturan_dashboard/dashboard_jabatanlainnya', [
            'dashboard' => $dashboard
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $data = [
            'tipe' => 'jabatan_lainnya',
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'judul_ami' => $this->request->getPost('judul_ami'),
            'judul_profil' => $this->request->getPost('judul_profil'), // akreditasi
        ];

        if ($id) {
            $this->dashboardModel->update($id, $data);
        } else {
            $this->dashboardModel->insert($data);
        }

        return redirect()->to(base_url('pengaturan-dashboard/dashboard-jabatanlainnya'))
            ->with('success', 'Pengaturan Dashboard Jabatan Lainnya berhasil disimpan!');
    }
}
