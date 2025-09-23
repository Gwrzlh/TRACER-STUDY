<?php

namespace App\Controllers\Organisasi;

use App\Models\Organisasi\JurusanModel;
use App\Models\Organisasi\SatuanOrganisasiModel;
use App\Models\Organisasi\Prodi;
use App\Models\Organisasi\TipeOrganisasiModel;
use CodeIgniter\Controller;

class SatuanOrganisasi extends Controller
{
    protected $helpers = ['form'];

    public function index()
    {
        $satuanModel = new SatuanOrganisasiModel();
        $jurusanModel = new JurusanModel();
        $prodiModel  = new Prodi();
        $tipeModel   = new TipeOrganisasiModel();

        $keyword = $this->request->getGet('keyword');

        // Hitung jumlah
        $data['count_satuan']  = $satuanModel->countAll();
        $data['count_jurusan'] = $jurusanModel->countAll();
        $data['count_prodi']   = $prodiModel->countAll();

        // Query utama (join tipe & prodi)
        $builder = $satuanModel
            ->select('satuan_organisasi.*, tipe_organisasi.nama_tipe, prodi.nama_prodi')
            ->join('tipe_organisasi', 'tipe_organisasi.id = satuan_organisasi.id_tipe', 'left')
            ->join('prodi', 'prodi.id = satuan_organisasi.id_prodi', 'left');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('satuan_organisasi.nama_satuan', $keyword)
                ->orLike('satuan_organisasi.nama_singkatan', $keyword)
                ->orLike('tipe_organisasi.nama_tipe', $keyword)
                ->orLike('prodi.nama_prodi', $keyword)
                ->groupEnd();
        }

        $data['satuan'] = $builder->findAll();
        $data['keyword'] = $keyword;

        return view('adminpage/organisasi/satuanorganisasi/index', $data);
    }

    public function create()
    {
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe'] = (new TipeOrganisasiModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_satuan'    => 'required|min_length[3]|max_length[50]',
            'nama_singkatan' => 'required|min_length[1]|max_length[10]',
            'nama_slug'      => 'required|min_length[3]|max_length[50]',
            'id_tipe'        => 'required|integer',
            'id_jurusan'     => 'required|integer',
            'id_prodi'       => 'required|integer',
            'deskripsi'      => 'permit_empty|max_length[255]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        (new SatuanOrganisasiModel())->insert([
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $this->request->getPost('nama_slug'),
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'id_jurusan'     => $this->request->getPost('id_jurusan'),
            'id_prodi'       => $this->request->getPost('id_prodi'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->to('/satuanorganisasi')->with('success', 'Data berhasil ditambahkan.');
    }

   public function edit($id)
{
    $model = new SatuanOrganisasiModel();
    $data['satuan'] = $model->find($id);
    $data['jurusan'] = (new JurusanModel())->findAll();
    $data['tipe'] = (new TipeOrganisasiModel())->findAll();
    return view('adminpage/organisasi/satuanorganisasi/edit', $data);
}

public function update($id) 
{
    $model = new SatuanOrganisasiModel();
    if ($model->update($id, $this->request->getPost())) {
        return redirect()->to('/satuanorganisasi')
                         ->with('success', 'Data berhasil diubah.');
    } else {
        return redirect()->to('/satuanorganisasi')
                         ->with('error', 'Gagal mengubah data.');
    }
}

public function delete($id)
{
    $model = new SatuanOrganisasiModel();
    if ($model->delete($id)) {
        return redirect()->to('/satuanorganisasi')
                         ->with('delete', 'Data berhasil dihapus.');
    } else {
        return redirect()->to('/satuanorganisasi')
                         ->with('error', 'Gagal menghapus data.');
    }
}

    // AJAX get Prodi by Jurusan
    public function getProdi($id_jurusan)
    {
        $prodiModel = new Prodi();
        $prodi = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();
        return $this->response->setJSON($prodi);
    }
}
