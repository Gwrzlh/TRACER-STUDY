<?php

namespace App\Controllers;

use App\Models\AlumniModel;
use CodeIgniter\Controller;

class AlumniController extends Controller
{
    protected $alumniModel;

    public function __construct()
    {
        $this->alumniModel = new AlumniModel();
    }

    // Untuk alumni biasa
    public function index()
    {
        $data['alumni'] = [
            ['nama' => 'Budi', 'prodi' => 'Teknik Informatika', 'tahun_lulus' => 2020, 'status_pekerjaan' => 'Bekerja'],
            ['nama' => 'Siti', 'prodi' => 'Teknik Mesin', 'tahun_lulus' => 2019, 'status_pekerjaan' => 'Wirausaha'],
        ];

        return view('alumni/index', $data);
    }


    // Untuk surveyor
  public function surveyor()
{
    $data['alumni_surveyor'] = [
        ['id' => 1, 'nama' => 'Andi', 'prodi' => 'Teknik Elektro', 'tahun_lulus' => 2018, 'status_pekerjaan' => 'Peneliti'],
        ['id' => 2, 'nama' => 'Rina', 'prodi' => 'Teknik Sipil', 'tahun_lulus' => 2017, 'status_pekerjaan' => 'Dosen'],
    ];

    return view('alumni/indexsurveyor', $data);
}


     public function dashboard()
{
    return view('alumni/dashboard');
}


    
}
