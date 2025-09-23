<?php
namespace App\Controllers;

use App\Models\User\AccountModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends \CodeIgniter\Controller
{
    protected $request;
    protected $helpers = ['cookie']; // Helper untuk get_cookie()

    /**
     * Inisialisasi controller, memeriksa session dan cookie untuk otentikasi
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Ambil instance session
        $session = session();

        // Cek apakah user belum login dan ada cookie remember_token
        if (!$session->get('logged_in')) {
            $rememberToken = get_cookie('remember_token');

            if ($rememberToken) {
                $username = base64_decode($rememberToken);

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