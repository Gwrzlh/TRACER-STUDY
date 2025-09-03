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

        // Jika login via session biasa (tanpa cookie)
        if ($session->get('logged_in') && $session->get('via_cookie') !== true) {
            // Cek JS sessionStorage akan logout jika tab baru
            return $this->redirectByRole($session->get('role_id'));
        }

        // Auto-login via cookie
        $rememberToken = $request->getCookie('remember_token');
        if ($rememberToken) {
            $decoded   = explode('|', base64_decode($rememberToken));
            $username  = $decoded[1] ?? null;

            if ($username) {
                $model = new AccountModel();
                $user  = $model->getByUsernameOrEmail($username);

                if ($user && $user['status'] === 'Aktif') {
                    $session->set([
                        'id'          => $user['id'],
                        'username'    => $user['username'],
                        'email'       => $user['email'],
                        'role_id'     => $user['id_role'],
                        'id_surveyor' => $user['id_surveyor'],
                        'logged_in'   => true,
                        'via_cookie'  => true
                    ]);
                    return $this->redirectByRole($user['id_role']);
                } else {
                    $response->deleteCookie('remember_token', '/');
                }
            }
        }

        return view('login', [
            'server_logged_in' => $session->get('logged_in') ? true : false,
            'via_cookie'       => $session->get('via_cookie') ? true : false
        ]);
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

    $user = $model->getByUsernameOrEmail($usernameOrEmail);

    if ($user && password_verify($password, $user['password']) && $user['status'] === 'Aktif') {
        // ✅ ambil data alumni berdasarkan id_account
        $db = db_connect();
        $detail = $db->table('detailaccount_alumni')
                     ->where('id_account', $user['id'])
                     ->get()
                     ->getRowArray();

        $sessionData = [
            'id'          => $user['id'],          // id dari tabel account
            'id_account'  => $user['id'],          // untuk relasi ke detailaccount_alumni
            'username'    => $user['username'],
            'email'       => $user['email'],
            'role_id'     => $user['id_role'],
            'id_surveyor' => $user['id_surveyor'],
            'logged_in'   => true
        ];

        // ✅ jika ada detail alumni, simpan juga nama lengkap & foto
        if ($detail) {
            $sessionData['nama_lengkap'] = $detail['nama_lengkap'];
            $sessionData['foto'] = $detail['foto'] ?? null; // tambahkan foto ke session
        }

        if ($remember) {
            $sessionData['via_cookie'] = true;
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
            $sessionData['via_cookie'] = false;
            $response->deleteCookie('remember_token', '/');
        }

        $session->set($sessionData);

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
}
