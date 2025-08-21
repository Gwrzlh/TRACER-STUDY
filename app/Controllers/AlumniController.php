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




    // public function isi()
    // {
    //     // Dummy data, nanti diganti dari DB
    //     $data['alumni'] = [
    //         ['no' => 1, 'nim' => '2021001', 'nama' => 'Budi', 'status' => 'Finish'],
    //         ['no' => 2, 'nim' => '2021002', 'nama' => 'Siti', 'status' => 'Belum Mengisi'],
    //         ['no' => 3, 'nim' => '2021003', 'nama' => 'Andi', 'status' => 'Ongoing'],
    //     ];

    //     return view('alumni/isi', $data);
    // }

    // public function teman()
    // {
    //     // Dummy teman alumni
    //     $data['teman'] = [
    //         ['nim' => '2021004', 'nama' => 'Rina', 'prodi' => 'Teknik Industri'],
    //         ['nim' => '2021005', 'nama' => 'Agus', 'prodi' => 'Teknik Mesin'],
    //     ];

    //     return view('alumni/teman', $data);
    // }

    // public function form($nim)
    // {
    //     // Untuk contoh, kita cari data berdasarkan NIM dari dummy array
    //     $alumni = [
    //         '2021001' => ['nama' => 'Budi'],
    //         '2021002' => ['nama' => 'Siti'],
    //         '2021003' => ['nama' => 'Andi'],
    //     ];

    //     $data = [
    //         'nim' => $nim,
    //         'nama' => $alumni[$nim]['nama'] ?? 'Tidak Diketahui'
    //     ];

    //     return view('alumni/form', $data);
    // }

}
