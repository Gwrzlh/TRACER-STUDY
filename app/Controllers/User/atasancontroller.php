<?php

namespace App\Controllers\User;

use CodeIgniter\Controller;

class AtasanController extends Controller
{
    public function dashboard()
    {
        // Hanya atasan yang boleh masuk
        if (session('role_id') != 8) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('atasan/dashboard');
    }
}
