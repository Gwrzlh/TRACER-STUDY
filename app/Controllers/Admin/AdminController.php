<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User\Accounts;
use App\Models\User\AccountModel;
use App\Models\Support\Roles;
use App\Models\User\DetailaccountAlumni;
use App\Models\User\DetailaccountAdmins;
use App\Models\User\DetailaccountKaprodi;
use App\Models\User\DetailaccoountPerusahaan;
use App\Models\User\DetailaccountAtasan;
use App\Models\User\DetailaccountJabatanLLnya;

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
        $detailPerusahaanModel = new DetailaccoountPerusahaan();
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
   
public function profil()
{
    $accountModel = new Accounts();
    $detailAdminModel = new DetailaccountAdmins();

    $id = session()->get('id_account');

    // Join account + detailaccount_admin
    $admin = $accountModel
        ->select('account.*, detailaccount_admin.nama_lengkap, detailaccount_admin.no_hp')
        ->join('detailaccount_admin', 'detailaccount_admin.id_account = account.id', 'left')
        ->where('account.id', $id)
        ->first();

    return view('adminpage/profil/index', [
        'title' => 'Profil Admin',
        'admin' => $admin
    ]);
}

public function updateFoto($id)
{
    $accountModel = new Accounts();

    $file = $this->request->getFile('foto');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/foto_admin', $newName);

        // update database
        $accountModel->update($id, [
            'foto' => $newName
        ]);

        // update session juga supaya sidebar langsung ikut berubah
        session()->set('foto', $newName);

        return $this->response->setJSON([
            'status' => 'success',
            'fotoUrl' => base_url('uploads/foto_admin/' . $newName)
        ]);
    }

    return $this->response->setJSON([
        'status' => 'error',
        'message' => 'File tidak valid atau gagal diupload.'
    ]);
}
public function editProfil($id)
{
    // Cek biar admin hanya bisa edit profilnya sendiri
    if (session()->get('id_account') != $id) {
        return redirect()->to('/admin/profil')->with('error', 'Tidak boleh edit profil orang lain.');
    }

    $accountModel = new Accounts();

    // join dengan detailaccount_admin
    $admin = $accountModel
        ->select('account.*, detailaccount_admin.nama_lengkap, detailaccount_admin.no_hp')
        ->join('detailaccount_admin', 'detailaccount_admin.id_account = account.id', 'left')
        ->where('account.id', $id)
        ->first();

    return view('adminpage/profil/edit', [
        'title' => 'Edit Profil Admin',
        'admin' => $admin
    ]);
}

public function updateProfil($id)
{
    if (session()->get('id_account') != $id) {
        return redirect()->to('/admin/profil')->with('error', 'Tidak boleh update profil orang lain.');
    }

    $accountModel = new Accounts();
    $detailAdminModel = new DetailaccountAdmins();

    // Validasi dengan pengecualian ID
    $rules = [
        'nama_lengkap' => 'required|min_length[3]',
        'no_hp'        => 'required|min_length[10]|max_length[20]',
        'username'     => 'required|min_length[3]|is_unique[account.username,id,' . $id . ']',
        'email'        => 'required|valid_email|is_unique[account.email,id,' . $id . ']',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Update tabel account
    $accountModel->update($id, [
        'username' => $this->request->getPost('username'),
        'email'    => $this->request->getPost('email'),
    ]);

    // Update detailaccount_admin
    $detailAdminModel
        ->where('id_account', $id)
        ->set([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'no_hp'        => $this->request->getPost('no_hp'),
        ])
        ->update();

    return redirect()->to('/admin/profil')->with('success', 'Profil berhasil diperbarui');
}

public function ubahPassword()
    {
       
    return view('adminpage/profil/ubah_password', [
        'title' => 'Ubah Password'
    ]);
    }

  public function updatePassword()
{
    $accountModel = new AccountModel();
    $id = session()->get('id_account'); // id dari session login

    $oldPassword = $this->request->getPost('old_password');
    $newPassword = $this->request->getPost('new_password');
    $confirmPassword = $this->request->getPost('confirm_password');

    $user = $accountModel->find($id);
    if (!$user) {
        return redirect()->to(base_url('admin/profil'))->with('error', 'Akun tidak ditemukan');
    }

    if (!password_verify($oldPassword, $user['password'])) {
        return redirect()->to(base_url('admin/profil'))->with('error', 'Password lama salah');
    }

    if ($newPassword !== $confirmPassword) {
        return redirect()->to(base_url('admin/profil'))->with('error', 'Password baru tidak sama dengan konfirmasi');
    }

    if (strlen($newPassword) < 6) {
        return redirect()->to(base_url('admin/profil'))->with('error', 'Password baru minimal 6 karakter');
    }

    if (password_verify($newPassword, $user['password'])) {
        return redirect()->to(base_url('admin/profil'))->with('error', 'Password baru tidak boleh sama dengan password lama');
    }

    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
    $accountModel->update($id, ['password' => $hashed]);

    return redirect()->to(base_url('admin/profil'))->with('success', 'Password berhasil diubah');
}

}