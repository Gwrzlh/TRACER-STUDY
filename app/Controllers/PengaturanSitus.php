<?php

namespace App\Controllers;

class PengaturanSitus extends BaseController
{
    public function index()
    {
        // Memanggil view yang ada di dalam folder adminpage/pengaturansitus
        return view('adminpage/pengaturansitus/index');
    }
}
