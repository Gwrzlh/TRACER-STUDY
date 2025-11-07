<?php

namespace App\Controllers;

use App\Models\PengaturanDashboardModel;
use CodeIgniter\Controller;

class PengaturanDashboardAtasan extends BaseController
{
    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new PengaturanDashboardModel();
    }

    public function index()
    {
        // 🔹 Ambil data dashboard atasan (tipe = 'atasan')
        $dashboard = $this->dashboardModel
            ->where('tipe', 'atasan')
            ->first();

        return view('adminpage/pengaturan_dashboard/dashboard_atasan', [
            'dashboard' => $dashboard
        ]);
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $data = [
            'tipe' => 'atasan',
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),

            // 🔹 Card 1 - 7 (sesuaikan kebutuhan di dashboard atasan)
            'judul_kuesioner'   => $this->request->getPost('card_1'), // contoh: Total Perusahaan
            'judul_data_alumni' => $this->request->getPost('card_2'), // contoh: Grafik Pertumbuhan Alumni
            'judul_profil'      => $this->request->getPost('card_3'), // contoh: Daftar Alumni Terbaru
            'judul_ami'         => $this->request->getPost('card_4'),
            'card_5'            => $this->request->getPost('card_5'),
            'card_6'            => $this->request->getPost('card_6'),
            'card_7'            => $this->request->getPost('card_7'),
        ];

        // 🔹 Insert atau update
        if ($id) {
            $this->dashboardModel->update($id, $data);
        } else {
            $this->dashboardModel->insert($data);
        }

        // 🔹 Redirect dengan pesan sukses
        return redirect()->to(base_url('pengaturan-dashboard/dashboard-atasan'))
            ->with('success', 'Pengaturan Dashboard Atasan berhasil disimpan!');
    }
}
