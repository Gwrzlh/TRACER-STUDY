<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Tipeorganisasi;
use App\models\Roles;

class TipeOrganisasiController extends BaseController
{
    public function index()
    {
        $tipeorganisasi = new Tipeorganisasi();
       $data = [
        'Tipeorganisasi'  =>  $tipeorganisasi->getgroupid()
       ];                                                                   

        return view('adminpage\organisasi\tipe_organisasi\index', $data);
    }
    public function create()
    {
        $tipeorganisasi = new Tipeorganisasi();
        $roles = new Roles();
        $data = [
            'roles' => $roles->findAll()
        ];

        return view('adminpage\organisasi\tipe_organisasi\tambah', $data);
    }
    public function store()
    {
       $validation = \Config\Services::validation();
       // validation
       $validation->setRules([
           'nama_tipe'  =>  'required|min_length[3]|max_length[100]',
           'lavel'      => 'required|integer',
           'deskripsi'  => 'permit_empty|max_length[255]',
           'group'      => 'required'
       ]);
    //   insert data
       $data = ([
        'nama_tipe' => $this->request->getPost('nama_tipe'),
        'level'     => $this->request->getPost('lavel'),
        'deskripsi' => $this->request->getPost('deskripsi'),
        'id_group'  => $this->request->getPost('group')
       ]);

       $tipeModel = new Tipeorganisasi();
       $tipeModel->insert($data);

      return redirect()->to('/admin/tipeorganisasi')->with('success', 'Data pengguna berhasil disimpan.');

    }
}
