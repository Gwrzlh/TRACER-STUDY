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
        $response = service('response');

        // Jika sudah login, langsung ke dashboard sesuai role
        if ($session->get('logged_in')) {
            return $this->redirectByRole($session->get('role_id'));
        }

        // Cek cookie remember_token
        $rememberToken = $request->getCookie('remember_token');
        if ($rememberToken) {
            $decoded   = explode('|', base64_decode($rememberToken));
            $username  = $decoded[1] ?? null;

            if ($username) {
                $model = new AccountModel();
                $user  = $model->getByUsernameOrEmail($username);

                if ($user) {
                    $session->set([
                        'id'          => $user['id'],
                        'username'    => $user['username'],
                        'email'       => $user['email'],
                        'role_id'     => $user['id_role'],
                        'id_surveyor' => $user['id_surveyor'],
                        'logged_in'   => true
                    ]);

                    return $this->redirectByRole($user['id_role']);
                }
            }
        }

        return view('login'); // tampilkan form login
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

        // Cari user di DB
        $user = $model->getByUsernameOrEmail($usernameOrEmail);

        if ($user && password_verify($password, $user['password']) && $user['status'] === 'Aktif') {

            // simpan session
            $session->set([
                'id'          => $user['id'],
                'username'    => $user['username'],
                'email'       => $user['email'],
                'role_id'     => $user['id_role'],
                'id_surveyor' => $user['id_surveyor'], // ✅ penting untuk alumni supervisi
                'logged_in'   => true
            ]);

            // simpan cookie jika remember me
            if ($remember) {
                $response->setCookie([
                    'name'     => 'remember_token',
                    'value'    => base64_encode($user['id_role'] . '|' . $user['username']),
                    'expire'   => 60 * 60 * 24 * 7, // 7 hari
                    'path'     => '/',
                    'httponly' => true,
                    'secure'   => false,
                    'samesite' => 'Lax'
                ]);
            } else {
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

    /**
     * Redirect berdasarkan role user
     */
    private function redirectByRole($roleId)
    {
        switch ($roleId) {
            case 1: // Alumni
                if (session('id_surveyor') == 1) {
                    return redirect()->to('alumni/supervisi'); // Alumni dengan hak supervisi
                }
                return redirect()->to('alumni/dashboard');

            case 2: // Admin
                return redirect()->to('admin/dashboard');

            case 6: // Kaprodi
                if (session('id_surveyor') == 1) {
                    return redirect()->to('kaprodi/supervisi');
                }
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
