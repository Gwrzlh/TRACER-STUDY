<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;

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
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('adminpage/dashboard');
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
