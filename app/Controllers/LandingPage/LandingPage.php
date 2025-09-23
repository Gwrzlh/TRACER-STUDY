<?php
namespace App\Controllers\LandingPage;

use App\Controllers\BaseController;

class LandingPage extends BaseController
{
    /**
     * Menampilkan halaman utama (index)
     */
    public function index()
    {
        return view('LandingPage/Homepage');
    }

    /**
     * Menampilkan halaman home
     */
    public function home()
    {
        return view('LandingPage/Homepage');
    }
}