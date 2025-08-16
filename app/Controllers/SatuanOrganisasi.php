<?php

namespace App\Controllers;

use App\Models\SatuanOrganisasiModel;
use App\Models\JurusanModel;
use App\Models\Prodi;
use App\Models\TipeOrganisasiModel;
use CodeIgniter\Controller;

class SatuanOrganisasi extends Controller
{
    protected $helpers = ['form'];

   public function index()
{
    $satuanModel = new SatuanOrganisasiModel();
    $jurusanModel = new JurusanModel();
    $prodiModel   = new Prodi();

    // Ambil keyword dari GET
    $keyword = $this->request->getGet('keyword');

    // Query untuk badge (hitung total tanpa filter)
    $data['count_satuan']  = $satuanModel->countAll();
    $data['count_jurusan'] = $jurusanModel->countAll();
    $data['count_prodi']   = $prodiModel->countAll();

    // Jika ada keyword, filter nama_satuan atau nama_singkatan
    if ($keyword) {
        $satuanModel->groupStart()
                    ->like('nama_satuan', $keyword)
                    ->orLike('nama_singkatan', $keyword)
                    ->groupEnd();
    }

    // Ambil data Satuan Organisasi + tipe
    $data['satuan'] = $satuanModel->getWithTipe();
    $data['keyword'] = $keyword; // supaya input search tetap terisi

    return view('adminpage/organisasi/satuanorganisasi/index', $data);
}


    public function create()
    {
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe']    = (new TipeOrganisasiModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_satuan'     => 'required|min_length[3]|max_length[50]',
            'nama_singkatan'  => 'required|min_length[1]|max_length[10]',
            'nama_slug'       => 'required|min_length[3]|max_length[50]',
            'deskripsi'       => 'permit_empty|max_length[255]',
            'id_tipe'         => 'required|integer',
            'urutan'          => 'permit_empty|integer',
            'satuan_induk'    => 'permit_empty|integer'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        (new SatuanOrganisasiModel())->insert([
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $this->request->getPost('nama_slug'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'urutan'         => $this->request->getPost('urutan'),
            'satuan_induk'   => $this->request->getPost('satuan_induk')
        ]);

        return redirect()->to('/satuanorganisasi')->with('success', 'Data ditambahkan.');
    }

    public function edit($id)
    {
        $model = new SatuanOrganisasiModel();
        $data['satuan']  = $model->find($id);
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe']    = (new TipeOrganisasiModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/edit', $data);
    }

    public function update($id)
    {
        (new SatuanOrganisasiModel())->update($id, $this->request->getPost());
        return redirect()->to('/satuanorganisasi')->with('success', 'Data diubah.');
    }

    public function delete($id)
    {
        (new SatuanOrganisasiModel())->delete($id);
        return redirect()->to('/satuanorganisasi')->with('success', 'Data dihapus.');
    }
}
