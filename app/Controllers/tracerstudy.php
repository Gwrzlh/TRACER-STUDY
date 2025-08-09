<?php

namespace App\Controllers;

use App\Models\KontakModel;
use App\Models\KontakDeskripsiModel;

class TracerStudy extends BaseController
{
    public function kontak()
    {
        $model = new KontakModel();

        // Ambil data berdasarkan tipe_kontak
        $surveyors    = $model->where('tipe_kontak', 'surveyor')->orderBy('urutan')->findAll();
        $model->resetQuery();

        $coordinators = $model->where('tipe_kontak', 'coordinator')->orderBy('urutan')->findAll();
        $model->resetQuery();

        $teams = $model->where('tipe_kontak', 'team')->orderBy('urutan')->findAll();
        $model->resetQuery();

        $directorates = $model->where('tipe_kontak', 'directorate')->orderBy('urutan')->findAll();
        $model->resetQuery();

        $address = $model->where('tipe_kontak', 'address')->first();

        // Ambil deskripsi (dari tabel kontak_deskripsi)
        $deskripsiModel = new KontakDeskripsiModel();
        $deskripsi = $deskripsiModel->first(); // ambil 1 baris (teks panjang)

        // Siapkan data ke view
        $data = [
            'surveyors'    => $surveyors,
            'coordinators' => $coordinators,
            'teams'        => $teams,
            'directorates' => $directorates,
            'address'      => $address,
            'deskripsi'    => $deskripsi, // kirim ke view
        ];

        return view('LandingPage/kontak', $data);
    }
}
