<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Accounts;
use App\Models\Roles;
use App\Models\DetailaccountAlumni;
use App\Models\DetailaccountAdmins;
use App\Models\DetailaccountKaprodi;
use App\Models\DetailaccountPerusahaan;
use App\Models\DetailaccountAtasan;
use App\Models\DetailaccountJabatanLLnya;
use App\Models\PesanModel;

class AdminController extends BaseController
{
    protected $pesanModel;
    public function __construct()
    {
         $this->pesanModel = new PesanModel();
    }
    
    public function index()
    {
        return view('adminpage/index');                             
    }

   public function dashboard()
{
    // 🔹 Model utama
    $accountModel = new Accounts();
    $rolesModel = new Roles();
    $detailAlumniModel = new DetailaccountAlumni();
    $detailAdminModel = new DetailaccountAdmins();
    $detailKaprodiModel = new DetailaccountKaprodi();
    $detailPerusahaanModel = new DetailaccountPerusahaan();
    $detailAtasanModel = new DetailaccountAtasan();
    $detailJabatanLainnyaModel = new DetailaccountJabatanLLnya();
    $dashboardModel = new \App\Models\PengaturanDashboardModel();

    // 🔹 Ambil pengaturan dashboard admin
    $dashboard = $dashboardModel->where('tipe', 'admin')->first();

    // 🔹 Ambil semua role
    $roles = $rolesModel->findAll();

    // 🔹 Hitung jumlah akun per role
    $counts = [];
    foreach ($roles as $role) {
        $counts[$role['id']] = $accountModel->where('id_role', $role['id'])->countAllResults();
        $accountModel->builder()->resetQuery();
    }

    // 🔹 Total semua akun
    $counts['all'] = $accountModel->countAllResults();

    // 🔹 Hitung response rate (contoh: alumni yang punya detail)
    $totalAlumni = $counts[1] ?? 0; // Role ID 1 = Alumni
    $alumniWithDetails = $detailAlumniModel->countAllResults();
    $responseRate = $totalAlumni > 0 ? round(($alumniWithDetails / $totalAlumni) * 100) : 0;

    // 🔹 Total survei (contoh: sama dengan total alumni yang isi detail)
    $totalSurvei = $alumniWithDetails;

    // 🔹 Data chart distribusi pengguna per role
    $userRoleData = [
        'labels' => [],
        'data' => []
    ];

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

    // 🔹 Data status pekerjaan (contoh statis)
    $statusPekerjaanData = [
        'labels' => ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Mencari Kerja'],
        'data' => [68, 15, 12, 5]
    ];

    // 🔹 Trend respons (contoh)
    $responseTrendData = [
        'labels' => ['Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus'],
        'data' => [65, 72, 68, 78, 82, $responseRate]
    ];

    // 🔹 Aktivitas terbaru
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

    // 🔹 Kirim data ke view
    $data = [
        'dashboard' => $dashboard,

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

        // Chart data
        'userRoleData' => $userRoleData,
        'statusPekerjaanData' => $statusPekerjaanData,
        'responseTrendData' => $responseTrendData,

        // Aktivitas
        'recentActivities' => $recentActivities,
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
public function kirimPeringatanPenilaian()
{
    $db = db_connect();
    $idAdmin = session()->get('id');
    $idAtasan = $this->request->getPost('id_atasan'); // kalau dikirim individual
    $pesanModel = $this->pesanModel;

    // Jika $idAtasan dikirim, berarti kirim individual
    if ($idAtasan) {
        $this->kirimPeringatanUntukAtasan($idAtasan, $idAdmin);
        return redirect()->back()->with('success', 'Peringatan berhasil dikirim ke atasan.');
    }

    // Kalau tidak ada id_atasan, berarti kirim ke semua
    $atasanList = $db->table('detailaccount_atasan da')
        ->select('da.id AS id_atasan, acc.id AS id_account')
        ->join('account acc', 'acc.id = da.id_account', 'left')
        ->get()->getResultArray();

    foreach ($atasanList as $atasan) {
        $this->kirimPeringatanUntukAtasan($atasan['id_account'], $idAdmin);
    }

    return redirect()->back()->with('success', 'Peringatan otomatis berhasil dikirim ke semua atasan.');
}

private function sendEmailBrevo(string $toEmail, string $subject, string $htmlContent): void
{
    $apiKey = getenv('BREVO_API_KEY');

    $data = [
        "sender" => [
            "email" => "tspolban@gmail.com",
            "name"  => "Tracer Study Polban"
        ],
        "to" => [["email" => $toEmail]],
        "subject"     => $subject,
        "htmlContent" => $htmlContent
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "accept: application/json",
        "api-key: {$apiKey}",
        "content-type: application/json",
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        log_message('error', 'Brevo API Error: ' . curl_error($ch));
    } else {
        log_message('info', "Brevo API Response ({$httpCode}): " . $response);
    }

    curl_close($ch);
}
public function peringatan()
{
    $db = db_connect();

    // Ambil semua atasan
    $atasanList = $db->table('detailaccount_atasan da')
        ->select('da.id AS id_atasan, da.nama_lengkap AS nama_atasan, acc.id AS id_account, acc.email')
        ->join('account acc', 'acc.id = da.id_account', 'left')
        ->get()->getResultArray();

    $dataPeringatan = [];

    foreach ($atasanList as $atasan) {
        // Alumni yang belum dinilai oleh atasan ini
        $alumniBelumDinilai = $db->table('atasan_alumni aa')
            ->select('al.id, al.nama_lengkap, al.nim, p.nama_prodi')
            ->join('detailaccount_alumni al', 'al.id = aa.id_alumni', 'left')
            ->join('prodi p', 'p.id = al.id_prodi', 'left')
            ->where('aa.id_atasan', $atasan['id_atasan'])
            ->whereNotIn('al.id', function ($builder) use ($atasan) {
                return $builder->select('id_alumni')
                    ->from('penilaian_alumni')
                    ->where('id_atasan', $atasan['id_atasan']);
            })
            ->get()
            ->getResultArray();

        if (!empty($alumniBelumDinilai)) {
            $dataPeringatan[] = [
                'atasan' => $atasan,
                'alumni' => $alumniBelumDinilai
            ];
        }
    }

    return view('adminpage/peringatan/index', [
        'peringatan' => $dataPeringatan
    ]);
}
private function kirimPeringatanUntukAtasan($idAtasan, $idAdmin)
{
    $db = db_connect();
    $pesanModel = $this->pesanModel;

    $atasan = $db->table('account')->where('id', $idAtasan)->get()->getRowArray();
    $detailAtasan = $db->table('detailaccount_atasan')->where('id_account', $idAtasan)->get()->getRowArray();

    if (!$atasan || !$detailAtasan) {
        log_message('error', "Data atasan tidak ditemukan: idAtasan={$idAtasan}");
        return;
    }

    // Cari alumni belum dinilai
    $alumni = $db->table('atasan_alumni aa')
        ->select('al.nama_lengkap')
        ->join('detailaccount_alumni al', 'al.id = aa.id_alumni', 'left')
        ->where('aa.id_atasan', $detailAtasan['id'])
        ->whereNotIn('al.id', function ($builder) use ($detailAtasan) {
            return $builder->select('id_alumni')
                ->from('penilaian_alumni')
                ->where('id_atasan', $detailAtasan['id']);
        })
        ->get()
        ->getResultArray();

    // Buat daftar alumni, walau kosong
    $daftarAlumni = !empty($alumni) ? implode(', ', array_column($alumni, 'nama_lengkap')) : 'Tidak ada alumni yang perlu dinilai.';

    // Simpan pesan dashboard
    $pesanModel->insert([
        'id_pengirim' => $idAdmin,
        'id_penerima' => $idAtasan,
        'pesan'       => "Anda belum memberikan penilaian kepada alumni: {$daftarAlumni}",
        'status'      => 'terkirim',
        'created_at'  => date('Y-m-d H:i:s'),
    ]);

    // Kirim email jika email tersedia
    if (!empty($atasan['email'])) {
        $subject = '🔔 Peringatan Penilaian Alumni Belum Dilakukan';
        $message = "
            <p>Halo <b>{$detailAtasan['nama_lengkap']}</b>,</p>
            <p>Berikut alumni yang belum Anda nilai:</p>
            <ul><li>" . (!empty($alumni) ? implode('</li><li>', array_column($alumni, 'nama_lengkap')) : 'Tidak ada') . "</li></ul>
            <p>Silakan segera menilai melalui dashboard Anda.</p>
            <br>
            <p>Salam,<br><b>Tracer Study Polban</b></p>
        ";

        log_message('info', "Kirim email ke: {$atasan['email']} dengan daftar alumni: {$daftarAlumni}");
        $this->sendEmailBrevo($atasan['email'], $subject, $message);
    } else {
        log_message('warning', "Atasan id={$idAtasan} tidak memiliki email. Tidak dapat mengirim notifikasi email.");
    }
}

}




