<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KontakDeskripsiModel;

class KontakDeskripsiController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new KontakDeskripsiModel();
    }

    public function index()
    {
        $data['deskripsi'] = $this->model->first();
        return view('adminpage/kontak_deskripsi/edit', $data); // arahkan ke file yang kamu pilih
    }

    public function update($id)
    {
        $isi = $this->request->getPost('isi');
        $this->model->update($id, ['isi' => $isi]);

        return redirect()->to(base_url('admin/kontak-deskripsi'))->with('success', 'Deskripsi berhasil diperbarui.');
    }
}
