<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class JabatanController extends Controller
{
    public function dashboard()
    {
        // Hanya jabatan lainnya yang boleh masuk
        if (session('role_id') != 9) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('jabatan/dashboard');
    }
}
