<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class KaprodiController extends Controller
{
    public function dashboard()
    {
        if (session('role_id') != 6 || session('id_surveyor')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('kaprodi/dashboard'); // Kaprodi biasa
    }

    public function supervisi()
    {
        if (session('role_id') != 6 || !session('id_surveyor')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('kaprodi/supervisi'); // Kaprodi dengan hak supervisi
    }
}
