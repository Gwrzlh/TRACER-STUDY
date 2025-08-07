<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use CodeIgniter\Controller;

class Jurusan extends Controller
{
    public function index()
    {
        $model = new JurusanModel();
        $data['jurusan'] = $model->findAll();
        return view('adminpage/organisasi/satuanorganisasi/jurusan/index', $data);
    }

    public function create()
    {
        return view('adminpage/organisasi/satuanorganisasi/jurusan/create');
    }

    public function store()
    {
        $model = new JurusanModel();
        $model->insert($this->request->getPost());
        return redirect()->to('/satuanorganisasi/jurusan')->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new JurusanModel();
        $data['jurusan'] = $model->find($id);
        return view('adminpage/organisasi/satuanorganisasi/jurusan/edit', $data);
    }

    public function update($id)
    {
        $model = new JurusanModel();
        $model->update($id, $this->request->getPost());
        return redirect()->to('/satuanorganisasi/jurusan')->with('success', 'Data jurusan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new JurusanModel();
        $model->delete($id);
        return redirect()->to('/satuanorganisasi/jurusan')->with('success', 'Data jurusan berhasil dihapus.');
    }
}
