<?php

namespace App\Controllers;

use App\Models\PengaturanDashboardModel;
use CodeIgniter\Controller;

class PengaturanDashboardAdmin extends BaseController
{
    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new PengaturanDashboardModel();
    }

    public function index()
    {
        // Ambil data dashboard admin (tipe = admin)
        $dashboard = $this->dashboardModel
            ->where('tipe', 'admin')
            ->first();

        return view('adminpage/pengaturan_dashboard/dashboard_admin', [
            'dashboard' => $dashboard
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $data = [
            'tipe' => 'admin',
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),

            // Card 1-7
            'judul_kuesioner'   => $this->request->getPost('card_1'),
            'judul_data_alumni' => $this->request->getPost('card_2'),
            'judul_profil'      => $this->request->getPost('card_3'),
            'judul_ami'         => $this->request->getPost('card_4'),
            'card_5'            => $this->request->getPost('card_5'),
            'card_6'            => $this->request->getPost('card_6'),
            'card_7'            => $this->request->getPost('card_7'),
        ];

        if ($id) {
            $this->dashboardModel->update($id, $data);
        } else {
            $this->dashboardModel->insert($data);
        }

        return redirect()->to(base_url('pengaturan-dashboard/dashboard-admin'))
            ->with('success', 'Pengaturan Dashboard Admin berhasil disimpan!');
    }
}
