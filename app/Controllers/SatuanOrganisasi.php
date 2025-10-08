<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JurusanModel;
use App\Models\SatuanOrganisasiModel;
use App\Models\Prodi;
use App\Models\TipeOrganisasiModel;

class SatuanOrganisasi extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
        $satuanModel  = new SatuanOrganisasiModel();
        $jurusanModel = new JurusanModel();
        $prodiModel   = new Prodi();
        $tipeModel    = new TipeOrganisasiModel();

        $keyword = $this->request->getGet('keyword');

        // Hitung jumlah
        $data['count_satuan']  = $satuanModel->countAll();
        $data['count_jurusan'] = $jurusanModel->countAll();
        $data['count_prodi']   = $prodiModel->countAll();

        // Ambil semua satuan + tipe
        $builder = $satuanModel
            ->select('satuan_organisasi.*, tipe_organisasi.nama_tipe')
            ->join('tipe_organisasi', 'tipe_organisasi.id = satuan_organisasi.id_tipe', 'left');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('satuan_organisasi.nama_satuan', $keyword)
                ->orLike('satuan_organisasi.nama_singkatan', $keyword)
                ->orLike('tipe_organisasi.nama_tipe', $keyword)
                ->groupEnd();
        }

        $satuan = $builder->findAll();

        // Ambil data prodi berdasarkan id_prodi (bukan semua prodi jurusan)
        foreach ($satuan as &$row) {
            $row['prodi'] = $prodiModel->find($row['id_prodi']);
        }

        $data['satuan']  = $satuan;
        $data['keyword'] = $keyword;

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
        $rules = [
            'nama_satuan'    => 'required|min_length[3]|max_length[50]',
            'nama_singkatan' => 'required|min_length[1]|max_length[10]',
            'nama_slug'      => 'required|min_length[3]|max_length[50]',
            'id_tipe'        => 'required|integer',
            'id_jurusan'     => 'required|integer',
            'id_prodi'       => 'required|integer',
            'deskripsi'      => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new SatuanOrganisasiModel();
        $slug  = $this->request->getPost('nama_slug');

        // Pastikan slug unik
        if ($model->where('nama_slug', $slug)->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'Slug sudah digunakan, coba yang lain.');
        }

        $model->insert([
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $slug,
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'id_jurusan'     => $this->request->getPost('id_jurusan'),
            'id_prodi'       => $this->request->getPost('id_prodi'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->to('/satuanorganisasi')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model           = new SatuanOrganisasiModel();
        $data['satuan']  = $model->find($id);
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe']    = (new TipeOrganisasiModel())->findAll();

        if (!$data['satuan']) {
            return redirect()->to('/satuanorganisasi')->with('error', 'Data tidak ditemukan.');
        }

        return view('adminpage/organisasi/satuanorganisasi/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_satuan'    => 'required|min_length[3]|max_length[50]',
            'nama_singkatan' => 'required|min_length[1]|max_length[10]',
            'nama_slug'      => 'required|min_length[3]|max_length[50]',
            'id_tipe'        => 'required|integer',
            'id_jurusan'     => 'required|integer',
            'id_prodi'       => 'required|integer',
            'deskripsi'      => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new SatuanOrganisasiModel();
        $slug  = $this->request->getPost('nama_slug');

        // Pastikan slug unik (kecuali slug milik record ini)
        $exists = $model->where('nama_slug', $slug)->where('id !=', $id)->countAllResults();
        if ($exists > 0) {
            return redirect()->back()->withInput()->with('error', 'Slug sudah digunakan oleh data lain.');
        }

        $model->update($id, [
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $slug,
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'id_jurusan'     => $this->request->getPost('id_jurusan'),
            'id_prodi'       => $this->request->getPost('id_prodi'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->to('/satuanorganisasi')->with('success', 'Data berhasil diubah.');
    }

    public function delete($id)
    {
        $model = new SatuanOrganisasiModel();

        if ($model->delete($id)) {
            return redirect()->to('/satuanorganisasi')->with('delete', 'Data berhasil dihapus.');
        }
        return redirect()->to('/satuanorganisasi')->with('error', 'Gagal menghapus data.');
    }

    // AJAX get Prodi by Jurusan
    public function getProdiByJurusan($id_jurusan)
    {
        $prodiModel = new Prodi();
        $prodi      = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();
        return $this->response->setJSON($prodi);
    }
}
