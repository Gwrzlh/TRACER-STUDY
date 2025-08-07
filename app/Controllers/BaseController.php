<?php

namespace App\Controllers;

use App\Models\AccountModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['cookie']; // tambahkan helper cookie agar bisa pakai get_cookie()

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Ambil instance session
        $session = session();

        // Cek apakah belum login dan ada cookie remember_token
        if (!$session->get('logged_in')) {
            $rememberToken = get_cookie('remember_token'); // helper bawaan CI

            if ($rememberToken) {
                $username = base64_decode($rememberToken); // decode dari cookie

                $model = new AccountModel();
                $user = $model->where('username', $username)->first();

                if ($user) {
                    $session->set([
                        'id'        => $user['id'],
                        'username'  => $user['username'],
                        'email'     => $user['email'],
                        'role_id'   => $user['id_role'],
                        'logged_in' => true
                    ]);
                }
            }
        }
    }
}
