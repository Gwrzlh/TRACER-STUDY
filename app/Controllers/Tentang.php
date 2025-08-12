<?php

namespace App\Controllers;

use App\Models\TentangModel;

class Tentang extends BaseController
{
    protected $tentangModel;

     public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

    // Halaman publik
    public function index()
    {
        $data['tentang'] = $this->tentangModel->first();
        return view('LandingPage/tentang', $data);
    }

    // Admin: form edit
    public function edit()
    {
        $data['tentang'] = $this->tentangModel->first();
        return view('adminpage/tentang/edit', $data);
    }

    // Admin: simpan perubahan
    public function update()
    {
        $id  = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $isi = $this->request->getPost('isi'); // TinyMCE kirim HTML, jangan strip_tags

        $this->tentangModel->update($id, [
            'judul' => $judul,
            'isi'   => $isi, // Simpan HTML apa adanya
        ]);
    return redirect()->to('/admin/tentang/edit')->with('success', 'Data berhasil diupdate.');
}

}
