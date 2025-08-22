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
        return view('alumni/questioner/index');
    }

    public function profil()
    {

    // tampil data profil
    return view('alumni/profil/index');
   
    }


    public function editProfil()
   {
    // tampil form edit profil
    return view('alumni/profil/edit');
    
   }

    public function supervisi()
    {
        return view('alumni/supervisi');
    }

    public function lihatTeman()
    {
        $alumniModel  = new \App\Models\DetailaccountAlumni();
        $jurusanModel = new \App\Models\JurusanModel();
        $prodiModel   = new \App\Models\Prodi();

        // Ambil data alumni yang sedang login
        $currentAlumni = $alumniModel
            ->where('id_account', session('id'))
            ->first();

        if (!$currentAlumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        // Ambil nama jurusan & prodi
        $jurusanNama = $jurusanModel->find($currentAlumni['id_jurusan'])['nama_jurusan'] ?? '-';
        $prodiNama   = $prodiModel->find($currentAlumni['id_prodi'])['nama_prodi'] ?? '-';

        // Cari alumni lain dengan jurusan & prodi sama
        $teman = $alumniModel
            ->where('id_jurusan', $currentAlumni['id_jurusan'])
            ->where('id_prodi', $currentAlumni['id_prodi'])
            ->where('id_account !=', session('id'))
            ->findAll();

        $data = [
            'teman'   => $teman,
            'jurusan' => $jurusanNama,
            'prodi'   => $prodiNama,
        ];

        return view('alumni/lihat_teman', $data);
    }
}
