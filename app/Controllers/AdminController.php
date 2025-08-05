<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function index()
    {
        return view('adminpage/index');                             
    }

    public function dashboard()
    {
        return view('adminpage/dashboard'); // Pastikan file view-nya ada
    }
}
