<?php

namespace App\Controllers\User;

use CodeIgniter\Controller;

class PerusahaanController extends Controller
{
    public function dashboard()
    {
        // Hanya perusahaan yang boleh masuk
        if (session('role_id') != 7) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('perusahaan/dashboard');
    }
}
