<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;
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

class Auth extends Controller
{
    public function login()
    {
        $session = session();
        $request = service('request');
        $response = service('response');

        // Jika sudah login, langsung ke dashboard
        if ($session->get('logged_in')) {
            return redirect()->to('dashboard');
        }

        // Cek apakah ada cookie remember_token
        $rememberToken = $request->getCookie('remember_token');

        if ($rememberToken) {
            $username = base64_decode($rememberToken);

            $model = new \App\Models\AccountModel();
            $user = $model->getByUsernameOrEmail($username);

            if ($user) {
                // Login otomatis
                $session->set([
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role_id'   => $user['id_role'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');
            }
        }

        // Kalau tidak ada session dan tidak ada cookie, tampilkan halaman login
        return view('login');
    }

    public function doLogin()
{
    $request = service('request');
    $session = session();
    $response = service('response');
    $model = new AccountModel();

    $usernameOrEmail = $request->getPost('username');
    $password = $request->getPost('password');
    $remember = $request->getPost('remember'); // ambil checkbox

    /// Login Alumni (statis untuk tes)
if ($usernameOrEmail === 'alumni' && $password === 'alumni123') {
    $session->set([
        'id'        => null,
        'username'  => 'alumni',
        'email'     => null,
        'role_id'   => 'alumni',
        'logged_in' => true
    ]);

    return redirect()->to('alumni/dashboard'); // arahkan ke halaman alumni
}

    // 🔹 Login normal via database
    $user = $model->getByUsernameOrEmail($usernameOrEmail);

    if ($user && password_verify($password, $user['password'])) {
        // Simpan ke session
        $session->set([
            'id'        => $user['id'],
            'username'  => $user['username'],
            'email'     => $user['email'],
            'role_id'   => $user['id_role'],
            'logged_in' => true
        ]);

        // Jika centang "Tetap login"
        if ($remember) {
            $response->setCookie([
                'name'     => 'remember_token',
                'value'    => base64_encode($user['username']),
                'expire'   => 60 * 60 * 24 * 7, // 7 hari
                'httponly' => true,
                'secure'   => false, // ubah ke true jika pakai HTTPS
            ]);
        }

        return redirect()->to('dashboard');
    }

    return redirect()->back()->with('error', 'Username atau password salah.');
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

        // Hitung response rate (contoh: berdasarkan alumni yang sudah mengisi detail lengkap)
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

    public function logout()
    {
        $response = service('response');
        session()->destroy();

        // Hapus cookie remember_token
        $response->deleteCookie('remember_token');         

        return redirect()->to(site_url('login')); // Gunakan helper agar konsisten

    }
}
