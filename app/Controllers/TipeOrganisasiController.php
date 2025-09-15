<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Tipeorganisasi;
use App\models\Roles;
use App\Models\TipeOrganisasiModel;

class TipeOrganisasiController extends BaseController
{
public function index()
{
    $perPage = $this->request->getGet('per_page') ?? 5; // default 5 data per halaman

    $tipeorganisasi = new \App\Models\Tipeorganisasi();

    $data = [
        'Tipeorganisasi' => $tipeorganisasi
                                ->getgroupid()
                                ->orderBy('id', 'DESC') // urutkan dari data terbaru
                                ->paginate($perPage),   // pagination
        'pager'   => $tipeorganisasi->pager,
        'perPage' => $perPage
    ];

    return view('adminpage/organisasi/tipe_organisasi/index', $data);
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
    public function edit($id){
        $roles = new Roles();
        $tipeOR = new TipeOrganisasiModel();

        $dataTpOr = $tipeOR->find($id);

        $data = [
            'roles' => $roles->findAll(),
            'datatpOr' => $dataTpOr
        ];

        return view('adminpage\organisasi\tipe_organisasi\edit', $data);
    }
    public function update($id)
{
    try {
        // Log start
        log_message('info', "Starting update for ID: $id");
        
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama_tipe' => 'required|min_length[3]|max_length[100]',
            'lavel'     => 'required|integer',
            'deskripsi' => 'permit_empty|max_length[255]',
            'group'     => 'required'
        ]);

        $inputData = [
            'nama_tipe' => $this->request->getPost('nama_tipe'),
            'lavel'     => $this->request->getPost('lavel'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'group'     => $this->request->getPost('group')
        ];

        // Debug input
        log_message('info', 'Input Data: ' . json_encode($inputData));

        if (!$validation->run($inputData)) {
            log_message('error', 'Validation Errors: ' . json_encode($validation->getErrors()));
            
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors())
                ->with('error', 'Ada kesalahan validasi, silakan periksa form Anda.');
        }

        // Prepare update data
        $updateData = [
            'nama_tipe' => trim($inputData['nama_tipe']),
            'lavel'     => (int) $inputData['lavel'],
            'deskripsi' => trim($inputData['deskripsi']),
            'id_group'  => (int) $inputData['group']
        ];

        log_message('info', 'Update Data: ' . json_encode($updateData));

        // Update to database
        $model = new TipeOrganisasiModel(); // Sesuaikan nama model
        
        if ($model->update($id, $updateData)) {
            log_message('info', "Update successful for ID: $id");
            
            return redirect()->to('/admin/tipeorganisasi')
                ->with('success', 'Data tipe organisasi berhasil diperbarui.');
        } else {
            log_message('error', "Update failed for ID: $id. Model errors: " . json_encode($model->errors()));
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }

    } catch (\Exception $e) {
        log_message('error', "Exception in update: " . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}
public function delete($id){
     $model = new TipeOrganisasiModel();
$datatipe = $model->find($id);

if ($datatipe) {
    $model->delete($id);
    return redirect()->to('/admin/tipeorganisasi')->with('success', 'Data dihapus.');
} else {
    return redirect()->to('/admin/tipeorganisasi')->with('error', 'Data tidak ditemukan.');
}

     
}
}
