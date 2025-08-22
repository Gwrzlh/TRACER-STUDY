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
        $session = session();

        // Jika login via session biasa (tanpa cookie)
        if ($session->get('logged_in') && $session->get('via_cookie') !== true) {
            // Cek tab baru pakai sessionStorage via JS
            // Kita akan tetap izinkan filter; logout paksa di controller login atau via JS
            return;
        }

        helper('cookie');
        $cookie = get_cookie('remember_token');

        if ($cookie) {
            $decoded = explode('|', base64_decode($cookie));
            $username = $decoded[1] ?? null;

            if ($username) {
                $model = new AccountModel();
                $user = $model->getByUsernameOrEmail($username);

                if ($user && $user['status'] === 'Aktif') {
                    $session->set([
                        'id'          => $user['id'],
                        'username'    => $user['username'],
                        'email'       => $user['email'],
                        'role_id'     => $user['id_role'],
                        'id_surveyor' => $user['id_surveyor'] ?? null,
                        'logged_in'   => true,
                        'via_cookie'  => true
                    ]);
                    return;
                } else {
                    service('response')->deleteCookie('remember_token', '/');
                }
            }
        }

        return redirect()->to('/login');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}
