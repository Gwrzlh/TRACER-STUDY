<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;
use App\Libraries\BrevoMailer;

class Auth extends Controller
{
    protected $accountModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
    }
    public function login()
    {
        // Kirim default value biar tidak error
        return view('login', [
            'server_logged_in' => session()->get('isLoggedIn') ?? false,
            'via_cookie'       => false, // kalau kamu tidak pakai "ingat saya"
        ]);
    }


    public function doLogin()
    {
        $request  = service('request');
        $session  = session();

        $usernameOrEmail = $request->getPost('username');
        $password        = $request->getPost('password');

        // Cari user berdasarkan username / email
        $user = $this->accountModel
            ->where('username', $usernameOrEmail)
            ->orWhere('email', $usernameOrEmail)
            ->first();

        if ($user && password_verify($password, $user['password']) && $user['status'] === 'Aktif') {
    $db = db_connect();

    // Ambil detail berdasarkan role
    $detail = null;
    if ($user['id_role'] == 1) { // Alumni
        $detail = $db->table('detailaccount_alumni')
            ->where('id_account', $user['id'])
            ->get()
            ->getRowArray();
    } elseif ($user['id_role'] == 6) { // Kaprodi
        $detail = $db->table('detailaccount_kaprodi')
            ->where('id_account', $user['id'])
            ->get()
            ->getRowArray();
    }

    // Data session dasar
    $sessionData = [
        'id'          => $user['id'],
        'id_account'  => $user['id'],
        'username'    => $user['username'],
        'email'       => $user['email'],
        'role_id'     => $user['id_role'],
        'id_surveyor' => $user['id_surveyor'],
        'logged_in'   => true,
    ];

    // Tambah data detail (kalau ada)
    if ($detail) {
        $sessionData['nama_lengkap'] = $detail['nama_lengkap'];
        $sessionData['foto']         = $detail['foto'] ?? 'default.png';
    } else {
        // fallback kalau tidak ada detail
        $sessionData['nama_lengkap'] = $user['username'];
        $sessionData['foto']         = 'default.png';
    }

    session()->set($sessionData);

    return $this->redirectByRole($user['id_role']);
}
        return redirect()->back()->with('error', 'Username atau password salah atau akun tidak aktif.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout.');
    }

    // Redirect sesuai role
    private function redirectByRole($roleId)
    {
        switch ($roleId) {
            case 1:
                return session('id_surveyor') == 1
                    ? redirect()->to('alumni/supervisi')
                    : redirect()->to('alumni/dashboard');
            case 2:
                return redirect()->to('admin/dashboard');
            case 6:
                return session('id_surveyor') == 1
                    ? redirect()->to('kaprodi/supervisi')
                    : redirect()->to('kaprodi/dashboard');
            case 7:
                return redirect()->to('perusahaan/dashboard');
            case 8:
                return redirect()->to('atasan/dashboard');
            case 9:
                return redirect()->to('jabatan/dashboard');
            default:
                return redirect()->to('/login');
        }
    }

    // Form lupa password
    public function forgotPassword()
    {
        return view('lupapassword');
    }

    // Kirim link reset password
    public function sendResetLink()
    {
        $email   = $this->request->getPost('email');
        $account = $this->accountModel->where('email', $email)->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }

        $token   = bin2hex(random_bytes(50));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->accountModel->setResetToken($account['id'], $token, $expires);

        $resetLink = base_url("resetpassword/$token");

        $mailer  = new BrevoMailer();
        $subject = "Reset Password Tracer Study";
        $htmlContent = "
            <p>Halo, {$account['username']}!</p>
            <p>Klik link berikut untuk reset password Anda:</p>
            <a href='$resetLink'>$resetLink</a>
            <p>Link berlaku 1 jam.</p>
        ";

        $sent = $mailer->sendEmail($account['email'], $account['username'], $subject, $htmlContent);

        if ($sent) {
            return redirect()->to('/login')->with('success', 'Link reset sudah dikirim ke email. Cek log untuk debug.');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim email. Cek log di writable/logs/');
        }
    }

    // Form reset password
    public function resetPassword($token)
    {
        $user = $this->accountModel->getUserByResetToken($token);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token tidak valid atau sudah kadaluarsa.');
        }

        return view('resetpassword', ['token' => $token]);
    }

    // Simpan password baru
    public function doResetPassword()
    {
        $token       = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');
        $confirm     = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirm) {
            return redirect()->back()->with('error', 'Password dan konfirmasi tidak sama.');
        }

        $user = $this->accountModel->getUserByResetToken($token);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Token tidak valid atau sudah kadaluarsa.');
        }

        $this->accountModel->updatePasswordAndExpire($user['id'], $newPassword);

        return redirect()->to('/login')->with('success', 'Password berhasil direset, silakan login.');
    }
}
