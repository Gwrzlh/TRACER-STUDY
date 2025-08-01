<?php

namespace App\Controllers;

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
        $usernameOrEmail = $request->getPost('username'); // bisa username atau email
        $password = $request->getPost('password');

        // Simulasi data user
        $users = [
            [
                'username' => 'admin',
                'email'    => 'admin@example.com',
                'password' => 'admin123',
                'role'     => 'admin'
            ],
            [
                'username' => 'alumni',
                'email'    => 'alumni@example.com',
                'password' => 'alumni123',
                'role'     => 'alumni'
            ],
            [
                'username' => 'perusahaan',
                'email'    => 'perusahaan@example.com',
                'password' => 'perusahaan123',
                'role'     => 'perusahaan'
            ],
            [
                'username' => 'jurusan',
                'email'    => 'jurusan@example.com',
                'password' => 'jurusan123',
                'role'     => 'jurusan'
            ]
        ];

        // Cek login pakai username ATAU email
        foreach ($users as $user) {
            if (
                ($usernameOrEmail === $user['username'] || $usernameOrEmail === $user['email']) &&
                $password === $user['password']
            ) {
                session()->set([
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');
            }
        }

        // Jika tidak cocok
        return redirect()->back()->with('error', 'Username atau password salah');
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function dashboard()
    {
        $role = session()->get('role');
        if ($role === 'admin') {
            return view('admin');
        } elseif ($role === 'alumni') {
            return view('alumni');
        } elseif ($role === 'perusahaan') {
            return view('perusahaan');
        } elseif ($role === 'jurusan') {
            return view('jurusan');
        }

        return redirect()->to('/login');
    }
}
