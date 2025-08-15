<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Accounts;
use App\Models\Roles;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountAdmins;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountPerusahaan;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountJabatanLLnya;

class AdminController extends BaseController
{
    public function index()
    {
        return view('adminpage/index');                             
    }

    public function dashboard()
    {
        // Model yang diperlukan
        $accountModel = new Accounts();
        $rolesModel = new Roles();
        $detailAlumniModel = new DetailaccountAlumni();
        $detailAdminModel = new DetailaccountAdmins();
        $detailKaprodiModel = new DetailaccountKaprodi();
        $detailPerusahaanModel = new DetailaccountPerusahaan();
        $detailAtasanModel = new DetailaccountAtasan();
        $detailJabatanLainnyaModel = new DetailaccountJabatanLLnya();

        // Ambil semua role
        $roles = $rolesModel->findAll();

        // Hitung jumlah pengguna per role (sama seperti di PenggunaController)
        $counts = [];
        foreach ($roles as $role) {
            $counts[$role['id']] = $accountModel->where('id_role', $role['id'])->countAllResults();
            $accountModel->builder()->resetQuery(); // Reset query untuk count berikutnya
        }
        
        // Total semua akun
        $counts['all'] = $accountModel->countAllResults();

        // Hitung response rate (contoh: berdasarkan alumni yang sudah mengisi survei)
        // Asumsi: response rate = (alumni dengan detail lengkap / total alumni) * 100
        $totalAlumni = $counts[1] ?? 0; // Role ID 1 = Alumni
        $alumniWithDetails = $detailAlumniModel->countAllResults();
        $responseRate = $totalAlumni > 0 ? round(($alumniWithDetails / $totalAlumni) * 100) : 0;

        // Hitung total survei (bisa disesuaikan dengan model survei Anda)
        // Untuk sementara, kita gunakan total detail alumni sebagai contoh
        $totalSurvei = $alumniWithDetails;

        // Data untuk chart - Distribusi Pengguna per Role
        $userRoleData = [
            'labels' => [],
            'data' => []
        ];

        // Mapping role ID ke nama untuk chart
        $roleMapping = [
            1 => 'Alumni',
            2 => 'Admin', 
            6 => 'Kaprodi',
            7 => 'Perusahaan',
            8 => 'Atasan',
            9 => 'Jabatan Lainnya'
        ];

        foreach ($roleMapping as $roleId => $roleName) {
            if (isset($counts[$roleId]) && $counts[$roleId] > 0) {
                $userRoleData['labels'][] = $roleName;
                $userRoleData['data'][] = $counts[$roleId];
            }
        }

        // Data untuk Status Pekerjaan Alumni (contoh data - bisa disesuaikan)
        $statusPekerjaanData = [
            'labels' => ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Mencari Kerja'],
            'data' => [68, 15, 12, 5] // Persentase - bisa diambil dari database
        ];

        // Data untuk Response Trend (contoh data - bisa disesuaikan dengan data real)
        $responseTrendData = [
            'labels' => ['Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus'],
            'data' => [65, 72, 68, 78, 82, $responseRate]
        ];

        // Aktivitas terbaru (contoh data - bisa disesuaikan)
        $recentActivities = [
            [
                'icon' => 'A',
                'title' => 'Alumni Baru Terdaftar',
                'description' => ($counts[1] ?? 0) . ' alumni terdaftar',
                'color' => '#10b981'
            ],
            [
                'icon' => 'S', 
                'title' => 'Survei Diselesaikan',
                'description' => $totalSurvei . ' survei telah diselesaikan',
                'color' => '#3b82f6'
            ],
            [
                'icon' => 'P',
                'title' => 'Perusahaan Bergabung', 
                'description' => ($counts[7] ?? 0) . ' perusahaan terdaftar',
                'color' => '#f59e0b'
            ],
            [
                'icon' => 'K',
                'title' => 'Kaprodi Aktif',
                'description' => ($counts[6] ?? 0) . ' kaprodi terdaftar',
                'color' => '#8b5cf6'
            ],
            [
                'icon' => 'A',
                'title' => 'Atasan Terdaftar',
                'description' => ($counts[8] ?? 0) . ' atasan terdaftar',
                'color' => '#ef4444'
            ]
        ];

        // Data yang akan dikirim ke view
        $data = [
            // Statistik utama
            'totalSurvei' => $totalSurvei,
            'responseRate' => $responseRate,
            'totalAlumni' => $counts[1] ?? 0,
            'totalAdmin' => $counts[2] ?? 0,
            'totalKaprodi' => $counts[6] ?? 0,
            'totalPerusahaan' => $counts[7] ?? 0,
            'totalAtasan' => $counts[8] ?? 0,
            'totalJabatanLainnya' => $counts[9] ?? 0,
            'totalAll' => $counts['all'],

            // Data untuk chart
            'userRoleData' => $userRoleData,
            'statusPekerjaanData' => $statusPekerjaanData,
            'responseTrendData' => $responseTrendData,

            // Aktivitas terbaru
            'recentActivities' => $recentActivities,

            // Data mentah counts untuk keperluan lain
            'counts' => $counts,
            'roles' => $roles
        ];

        return view('adminpage/dashboard', $data);
    }
}
