<?php

namespace App\Controllers\Organisasi;

use App\Models\Organisasi\JurusanModel;
use App\Models\Organisasi\SatuanOrganisasiModel;
use App\Models\Organisasi\Prodi;
use CodeIgniter\Controller;

class ProdiController extends Controller
{
    protected $helpers = ['form'];

    public function index()
    {
        $satuanModel  = new SatuanOrganisasiModel();
        $jurusanModel = new JurusanModel();
        $prodiModel   = new Prodi();

        // Ambil keyword dari GET
        $keyword = $this->request->getGet('keyword');

        // Query untuk badge (hitung total tanpa filter)
        $data['count_satuan']  = $satuanModel->countAll();
        $data['count_jurusan'] = $jurusanModel->countAll();
        $data['count_prodi']   = $prodiModel->countAll();

        // Ambil data Prodi + Join ke Jurusan
        $builder = $prodiModel->select('prodi.id, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = prodi.id_jurusan', 'left');

        // Filter jika ada keyword (pencarian nama_prodi atau nama_jurusan)
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('prodi.nama_prodi', $keyword)
                ->orLike('jurusan.nama_jurusan', $keyword)
                ->groupEnd();
        }

        $data['prodi']   = $builder->findAll();
        $data['keyword'] = $keyword;

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
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'id_jurusan' => $this->request->getPost('id_jurusan'),
        ];
        $model->insert($data);

        return redirect()->to('/satuanorganisasi/prodi')->with('success', 'Data prodi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $prodiModel   = new Prodi();
        $jurusanModel = new JurusanModel();

        $data['prodi']   = $prodiModel->find($id);
        $data['jurusan'] = $jurusanModel->findAll();

        return view('adminpage/organisasi/satuanorganisasi/prodi/edit', $data);
    }

    public function update($id)
    {
        $model = new Prodi();
        $data = [
            'nama_prodi' => $this->request->getPost('nama_prodi'),
            'id_jurusan' => $this->request->getPost('id_jurusan'),
        ];
        $model->update($id, $data);

        return redirect()->to('/satuanorganisasi/prodi')->with('success', 'Data prodi berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new Prodi();
        $model->delete($id);
        return redirect()->to('/satuanorganisasi/prodi')->with('success', 'Data prodi berhasil dihapus.');
    }
    public function getProdi($id_jurusan)
    {
        $prodiModel = new Prodi();
        $prodi = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();

        return $this->response->setJSON($prodi);
    }
}
