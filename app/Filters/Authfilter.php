<?php

namespace App\Filters;

use App\Models\AccountModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('cookie'); // ✅ Load helper agar bisa pakai get_cookie()

        $session = session();

        // Jika sudah login, lanjutkan
        if ($session->get('logged_in')) {
            return;
        }

        // Ambil cookie "remember_token"
        $cookie = get_cookie('remember_token'); // ✅ Sekarang aman digunakan

        if ($cookie) {
            $username = base64_decode($cookie);
            $model = new AccountModel();
            $user = $model->getByUsernameOrEmail($username);

            if ($user) {
                $session->set([
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'email'     => $user['email'],
                    'role_id'   => $user['id_role'],
                    'logged_in' => true
                ]);
                return; // izinkan lanjut ke halaman
            }
        }

        // Jika tidak login & tidak ada cookie, redirect ke login
        return redirect()->to('/login');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan
    }
}
