<?php

namespace App\Controllers;

use App\Models\JurusanModel;
use App\Models\SatuanOrganisasiModel;
use App\Models\Prodi;
use CodeIgniter\Controller;

class Jurusan extends Controller
{
   public function index()
{
    $jurusanModel = new JurusanModel();
    $satuanModel  = new SatuanOrganisasiModel();
    $prodiModel   = new Prodi();

    // Ambil keyword dari GET
    $keyword = $this->request->getGet('keyword');

    // Ambil setting jumlah per halaman (default 10)
    $perPage = get_setting('org_perpage_default', 10);

    // Query untuk badge (hitung total tanpa filter)
    $data['count_satuan']  = $satuanModel->countAll();
    $data['count_jurusan'] = $jurusanModel->countAll();
    $data['count_prodi']   = $prodiModel->countAll();

    // Filter jika ada keyword
    if ($keyword) {
        $jurusanModel->like('nama_jurusan', $keyword);
    }

    // Ambil data dengan pagination
    $jurusan = $jurusanModel
        ->orderBy('id', 'DESC')
        ->paginate($perPage);

    $data['jurusan']      = $jurusan;
    $data['pager']        = $jurusanModel->pager;
    $data['perPage']      = $perPage;
    $data['currentPage']  = (int) ($this->request->getGet('page') ?? 1);
    $data['pagerLinks']   = $jurusanModel->pager->links();
    $data['keyword']      = $keyword;

    return view('adminpage/organisasi/satuanorganisasi/jurusan/index', $data);
}


    public function create()
    {
        return view('adminpage/organisasi/satuanorganisasi/jurusan/create');
    }

    public function store()
    {
        $model = new JurusanModel();

        $model->insert([
            'nama_jurusan' => $this->request->getPost('nama_jurusan')
        ]);

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
        $model->update($id, [
            'nama_jurusan' => $this->request->getPost('nama_jurusan')
        ]);

        return redirect()->to('/satuanorganisasi/jurusan')->with('success', 'Data jurusan berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $model = new JurusanModel();
        $model->delete($id);

        return redirect()->to('/satuanorganisasi/jurusan')->with('success', 'Data jurusan berhasil ditambahkan.');
    }
}
