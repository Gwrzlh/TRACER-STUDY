<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        $session  = session();
        $request  = service('request');

        // Kalau sudah login → redirect sesuai role
        if ($session->get('logged_in')) {
            return $this->redirectByRole($session->get('role_id'));
        }

        // Auto-login via cookie remember_token
        $rememberToken = $request->getCookie('remember_token');
        if ($rememberToken) {
            $decoded = base64_decode($rememberToken, true);
            if ($decoded !== false) {
                $parts = explode('|', $decoded, 2);
                if (count($parts) === 2) {
                    [$roleId, $username] = $parts;

                    $model = new AccountModel();
                    $user  = $model->getByUsernameOrEmail($username);

                    if ($user && $user['id_role'] == $roleId && $user['status'] === 'Aktif') {
                        $session->set([
                            'id'        => $user['id'],
                            'username'  => $user['username'],
                            'email'     => $user['email'],
                            'role_id'   => $user['id_role'],
                            'logged_in' => true
                        ]);
                        return $this->redirectByRole($user['id_role']);
                    }
                }
            }
        }

        return view('login');
    }

    public function doLogin()
    {
        $request  = service('request');
        $session  = session();
        $response = service('response');
        $model    = new AccountModel();

        $usernameOrEmail = $request->getPost('username');
        $password        = $request->getPost('password');
        $remember        = $request->getPost('remember') == '1';

        // Cari user dari database
        $user = $model->getByUsernameOrEmail($usernameOrEmail);

        if ($user && password_verify($password, $user['password']) && $user['status'] === 'Aktif') {

            if ($remember) {
                // Simpan session biasa + cookie 7 hari
                $session->set([
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role_id'   => $user['id_role'],
                    'logged_in' => true
                ]);
                $response->setCookie([
                    'name'     => 'remember_token',
                    'value'    => base64_encode($user['id_role'] . '|' . $user['username']),
                    'expire'   => 60 * 60 * 24 * 7, // 7 hari
                    'path'     => '/',
                    'httponly' => true,
                    'secure'   => false, // true jika pakai HTTPS
                    'samesite' => 'Lax'
                ]);
            } else {
                // Session hanya berlaku selama browser terbuka
                $session->setTempdata([
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role_id'   => $user['id_role'],
                    'logged_in' => true
                ], null); // null = sampai browser ditutup
                $response->deleteCookie('remember_token', '/');
            }

            return $this->redirectByRole($user['id_role']);
        }

        return redirect()->back()->with('error', 'Username atau password salah atau akun tidak aktif.');
    }

    public function logout()
    {
        $response = service('response');
        session()->destroy();
        $response->deleteCookie('remember_token', '/');
        return redirect()->to(site_url('login'));
    }

    private function redirectByRole($roleId)
    {
        switch ($roleId) {
            case 1: // Alumni
                return redirect()->to('alumni/dashboard');
            case 2: // Admin
                return redirect()->to('admin/dashboard');
            case 6: // Kaprodi
                return redirect()->to('kaprodi/dashboard');
            case 7: // Perusahaan
                return redirect()->to('perusahaan/dashboard');
            case 8: // Atasan
                return redirect()->to('atasan/dashboard');
            case 9: // Jabatan lainnya
                return redirect()->to('jabatan/dashboard');
            default:
                return redirect()->to('/login');
        }
    }
}
