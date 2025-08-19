<?php

namespace App\Controllers;

use App\Models\KontakModel;
use CodeIgniter\Controller;

class Kontak extends Controller
{
    protected $kontakModel;
    protected $db;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
        $this->db = \Config\Database::connect();
    }

    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        return view('adminpage/kontak/index', [
            'wakilDirektur' => $this->getKontakByKategori('Wakil Direktur'),
            'teamTracer'    => $this->getKontakByKategori('Tim Tracer'),
            'surveyors'     => $this->getKontakByKategori('Surveyor')
        ]);
    }

    // ==============================
    // AJAX Search (cari kandidat by kategori)
    // ==============================
    public function search()
    {
        $kategori = $this->request->getGet('kategori');
        $keyword  = $this->request->getGet('keyword');

        $result = [];

        if ($kategori == 'Surveyor') {
            // cari by NIM
            $builder = $this->db->table('detailaccount_alumni da')
                ->select('da.nama_lengkap, da.nim, da.notlp, a.email, p.nama_prodi, j.nama_jurusan, da.tahun_kelulusan, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->join('prodi p', 'p.id = da.id_prodi', 'left')
                ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
                ->where('da.nim', $keyword);

            $result = $builder->get()->getRowArray();
        } elseif ($kategori == 'Tim Tracer') {
            // cari by nama
            $builder = $this->db->table('detailaccount_admin da')
                ->select('da.nama_lengkap, a.email, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->like('da.nama_lengkap', $keyword);

            $result = $builder->get()->getRowArray();
        } elseif ($kategori == 'Wakil Direktur') {
            // cari by nama
            $builder = $this->db->table('detailaccount_atasan da')
                ->select('da.nama_lengkap, da.notlp, a.email, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->like('da.nama_lengkap', $keyword);

            $result = $builder->get()->getRowArray();
        }

        return $this->response->setJSON($result ?: []);
    }

    // ==============================
    // Tambah kontak
    // ==============================
    public function store()
    {
        $kategori   = $this->request->getPost('kategori');
        $id_account = $this->request->getPost('id_account');

        if (!$kategori || !$id_account) {
            return redirect()->back()->with('error', 'Kategori dan data harus dipilih');
        }

        $this->kontakModel->insert([
            'kategori'   => $kategori,
            'id_account' => $id_account
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan');
    }

    // ==============================
    // Helper ambil kontak per kategori
    // ==============================
    private function getKontakByKategori($kategori)
    {
        $builder = $this->db->table('kontak k')
            ->join('account a', 'a.id = k.id_account', 'left');

        if ($kategori == 'Surveyor') {
            $builder->select('k.id as kontak_id, da.nama_lengkap, da.nim, da.notlp, a.email, p.nama_prodi, j.nama_jurusan, da.tahun_kelulusan')
                ->join('detailaccount_alumni da', 'da.id_account = a.id', 'left')
                ->join('prodi p', 'p.id = da.id_prodi', 'left')
                ->join('jurusan j', 'j.id = da.id_jurusan', 'left');
        } elseif ($kategori == 'Tim Tracer') {
            $builder->select('k.id as kontak_id, da.nama_lengkap, a.email')
                ->join('detailaccount_admin da', 'da.id_account = a.id', 'left');
        } elseif ($kategori == 'Wakil Direktur') {
            $builder->select('k.id as kontak_id, da.nama_lengkap, da.notlp, a.email')
                ->join('detailaccount_atasan da', 'da.id_account = a.id', 'left');
        }

        return $builder->where('k.kategori', $kategori)
            ->orderBy('k.id', 'DESC')
            ->get()->getResultArray();
    }
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID kontak tidak valid');
        }

        $this->kontakModel->delete($id);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus');
    }
    public function landing()
    {
        return view('landingpage/kontak', [
            'wakilDirektur' => $this->getKontakByKategori('Wakil Direktur'),
            'teamTracer'    => $this->getKontakByKategori('Tim Tracer'),
            'surveyors'     => $this->getKontakByKategori('Surveyor')
        ]);
    }
}
