<?php
namespace App\Controllers\LandingPage;

use App\Models\LandingPage\TentangModel;
use App\Controllers\BaseController;

class Tentang extends BaseController
{
    protected $tentangModel;

    public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

    public function index()
    {
        $data['tentang'] = $this->tentangModel->first();
        return view('LandingPage/tentang', $data);
    }

    public function edit()
    {
        $data['tentang'] = $this->tentangModel->first();
        return view('adminpage/tentang/edit', $data);
    }

    public function update()
    {
        $id    = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $isi   = $this->request->getPost('isi');

        $this->tentangModel->update($id, [
            'judul' => $judul,
            'isi'   => $isi,
        ]);

        return redirect()->to('admin/tentang/edit')->with('success', 'Data berhasil diupdate.');
    }
}
