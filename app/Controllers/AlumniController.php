<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;

class AlumniController extends BaseController
{
    public function dashboard()
    {
        return view('alumni/dashboard');
    }
  
    public function questioner()
   {
        // arahkan ke folder alumni/questioner/index.php
        return view('alumni/questioner/index');
    }

    public function supervisi()
    {

        return view('alumni/supervisi');
    }

    public function lihatTeman()
    {
        $alumniModel = new DetailaccountAlumni();

        // Ambil data alumni yang sedang login
        $currentAlumni = $alumniModel
            ->where('id_account', session('id'))
            ->first();

        if (!$currentAlumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        // Ambil alumni lain dengan jurusan & prodi sama + join status dari account
        $teman = $alumniModel
            ->select('detailaccount_alumni.*, account.status')
            ->join('account', 'account.id = detailaccount_alumni.id_account')
            ->where('id_jurusan', $currentAlumni['id_jurusan'])
            ->where('id_prodi', $currentAlumni['id_prodi'])
            ->where('detailaccount_alumni.id_account !=', session('id'))
            ->findAll();

        $data = [
            'teman'   => $teman,
            'jurusan' => $currentAlumni['id_jurusan'],
            'prodi'   => $currentAlumni['id_prodi'],
        ];

        return view('alumni/lihat_teman', $data);
    }




   
}
