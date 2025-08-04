<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        if ($db->connect()) {
            return "Koneksi database berhasil!";
        } else {
            return "Koneksi database gagal!";
        }
    }
}
