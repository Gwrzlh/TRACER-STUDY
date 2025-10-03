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

    /**
     * List semua satuan organisasi
     */
   public function index()
{
    $satuanModel = new SatuanOrganisasiModel();
    $jurusanModel = new JurusanModel();
    $prodiModel   = new Prodi();
    $tipeModel    = new TipeOrganisasiModel();

    $keyword = $this->request->getGet('keyword');

    // Hitung jumlah
    $data['count_satuan']  = $satuanModel->countAll();
    $data['count_jurusan'] = $jurusanModel->countAll();
    $data['count_prodi']   = $prodiModel->countAll();

    // Query satuan organisasi + tipe
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

    // urutkan data terbaru di atas
    $builder->orderBy('satuan_organisasi.id', 'DESC');

    // ambil default perPage dari setting
    $perPage = get_setting('org_perpage_default', 10);

    // paginate data
    $satuanList = $builder->paginate($perPage);

    $data['pager']   = $satuanModel->pager;
    $data['satuan']  = $satuanList;
    $data['keyword'] = $keyword;
    $data['perpage'] = $perPage;

    return view('adminpage/organisasi/satuanorganisasi/index', $data);
}


    /**
     * Form create
     */
    public function create()
    {
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe']    = (new TipeOrganisasiModel())->findAll();
        return view('adminpage/organisasi/satuanorganisasi/create', $data);
    }

    /**
     * Simpan data baru
     */
    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_satuan'    => 'required|min_length[3]|max_length[50]',
            'nama_singkatan' => 'required|min_length[1]|max_length[10]',
            'nama_slug'      => 'required|min_length[3]|max_length[50]',
            'id_tipe'        => 'required|integer',
            'id_jurusan'     => 'required|integer',
            'prodi_ids'      => 'required', // multiple prodi
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $satuanModel = new SatuanOrganisasiModel();
        $prodiIds    = $this->request->getPost('prodi_ids'); // array of prodi_id

        // Simpan ke tabel satuan_organisasi
        $satuanId = $satuanModel->insert([
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $this->request->getPost('nama_slug'),
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'id_jurusan'     => $this->request->getPost('id_jurusan'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ], true);

        // Simpan ke pivot
        $db = \Config\Database::connect();
        foreach ($prodiIds as $pid) {
            $db->table('satuan_prodi')->insert([
                'satuan_id' => $satuanId,
                'prodi_id'  => $pid,
            ]);
        }

        return redirect()->to('/satuanorganisasi')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $model           = new SatuanOrganisasiModel();
        $data['satuan']  = $model->find($id);
        $data['jurusan'] = (new JurusanModel())->findAll();
        $data['tipe']    = (new TipeOrganisasiModel())->findAll();

        // Ambil prodi yang sudah dipilih (pivot)
        $db = \Config\Database::connect();
        $data['selected_prodi'] = $db->table('satuan_prodi')
            ->select('prodi_id')
            ->where('satuan_id', $id)
            ->get()
            ->getResultArray();

        return view('adminpage/organisasi/satuanorganisasi/edit', $data);
    }

    /**
     * Update data
     */
    public function update($id)
    {
        $satuanModel = new SatuanOrganisasiModel();
        $prodiIds    = $this->request->getPost('prodi_ids'); // array baru

        // Update data satuan
        $satuanModel->update($id, [
            'nama_satuan'    => $this->request->getPost('nama_satuan'),
            'nama_singkatan' => $this->request->getPost('nama_singkatan'),
            'nama_slug'      => $this->request->getPost('nama_slug'),
            'id_tipe'        => $this->request->getPost('id_tipe'),
            'id_jurusan'     => $this->request->getPost('id_jurusan'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
        ]);

        // Hapus pivot lama → simpan ulang
        $db = \Config\Database::connect();
        $db->table('satuan_prodi')->where('satuan_id', $id)->delete();

        if (!empty($prodiIds)) {
            foreach ($prodiIds as $pid) {
                $db->table('satuan_prodi')->insert([
                    'satuan_id' => $id,
                    'prodi_id'  => $pid,
                ]);
            }
        }

        return redirect()->to('/satuanorganisasi')->with('success', 'Data berhasil diubah.');
    }

    /**
     * Delete data
     */
    public function delete($id)
    {
        $model = new SatuanOrganisasiModel();

        $db = \Config\Database::connect();
        $db->table('satuan_prodi')->where('satuan_id', $id)->delete(); // hapus relasi pivot dulu

        if ($model->delete($id)) {
            return redirect()->to('/satuanorganisasi')
                             ->with('delete', 'Data berhasil dihapus.');
        }

        return redirect()->to('/satuanorganisasi')
                         ->with('error', 'Gagal menghapus data.');
    }

    /**
     * AJAX ambil prodi berdasarkan jurusan
     */
    public function getProdi($id_jurusan)
    {
        $prodiModel = new Prodi();
        $prodi = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();
        return $this->response->setJSON($prodi);
    }

    public function getProdiByJurusan($id_jurusan)
    {
        $prodiModel = new Prodi();
        $data = $prodiModel->where('id_jurusan', $id_jurusan)->findAll();
        return $this->response->setJSON($data);
    }
}
