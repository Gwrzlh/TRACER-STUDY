<?php

namespace App\Controllers;

use App\Models\Prodi;
use App\Models\JurusanModel;
use CodeIgniter\Controller;

class ProdiController extends Controller
{
    protected $helpers = ['form'];

    public function index()
    {
        $model = new Prodi();
        $data['prodi'] = $model->getWithJurusan(); // ambil prodi + nama jurusan
        return view('adminpage/organisasi/satuanorganisasi/prodi/index', $data);
    }

    public function create()
    {
        $data['jurusan'] = (new JurusanModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/prodi/create', $data);
    }

    public function store()
    {
        $model = new Prodi();
        $data = [
            'nama_prodi'  => $this->request->getPost('nama_prodi'),
            'id_jurusan'  => $this->request->getPost('id_jurusan'),
        ];
        $model->insert($data);

        return redirect()->to('/satuanorganisasi/prodi')->with('success', 'Data prodi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new Prodi();
        $data['prodi'] = $model->find($id);
        $data['jurusan'] = (new JurusanModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/prodi/edit', $data);
    }

    public function update($id)
    {
        $model = new Prodi();
        $data = [
            'nama_prodi'  => $this->request->getPost('nama_prodi'),
            'id_jurusan'  => $this->request->getPost('id_jurusan'),
        ];
        $model->update($id, $data);

        return redirect()->to('/satuanorganisasi/prodi')->with('success', 'Data prodi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new Prodi();
        $model->delete($id);
        return redirect()->to('satuanorganisasi/prodi')->with('success', 'Data prodi berhasil dihapus.');
    }
}
