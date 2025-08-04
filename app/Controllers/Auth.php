<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        $request = service('request');
        $session = session();
        $model = new AccountModel();

        $usernameOrEmail = $request->getPost('username');
        $password = $request->getPost('password');

        $user = $model->getByUsernameOrEmail($usernameOrEmail);

        // Debug sementara (hapus ini jika login sudah berhasil)
        // dd($user);
        // dd(password_verify('password123', '$2y$10$uMm0YI1d6kfCRV09ulHYaevNYRj2cht3iU4evCbYzUcO9Sw/1DbQG'));
        // Tes manual password_verify
        // $plain = 'password123';
        // $hash  = '$2y$10$uMm0YI1d6kfCRV09ulHYaevNYRj2cht3iU4evCbYzUcO9Sw/1DbQG';

        // dd(password_verify($plain, $hash));

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'id'        => $user['id'],
                'username'  => $user['username'],
                'email'     => $user['email'],
                'role_id'   => $user['id_role'],
                'logged_in' => true
            ]);

            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // public function hashPasswordManual()
    // {
    //     $plain = 'password123';
    //     $hash = password_hash($plain, PASSWORD_DEFAULT);

    //     dd($hash);
    // }
    // public function generateHashAdmin()
    // {
    //     $plain = 'admin123';
    //     $hash = password_hash($plain, PASSWORD_DEFAULT);
    //     dd($hash);
    // }
}
