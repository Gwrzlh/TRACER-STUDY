<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminRespon extends BaseController
{
    public function index()
    {
        // menampilkan halaman utama respon
        return view('adminpage/respon/index');
    }
}
