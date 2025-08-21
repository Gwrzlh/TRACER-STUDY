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
    // INDEX (Admin Page)
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
            $builder = $this->db->table('detailaccount_alumni da')
                ->select('da.nama_lengkap, da.nim, da.notlp, da.tahun_kelulusan, a.email, p.nama_prodi, j.nama_jurusan, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->join('prodi p', 'p.id = da.id_prodi', 'left')
                ->join('jurusan j', 'j.id = da.id_jurusan', 'left')
                ->groupStart()
                ->where('da.nim', $keyword)
                ->orLike('da.nama_lengkap', $keyword)
                ->groupEnd();

            $result = $builder->get()->getResultArray(); // ✅ semua hasil
        } elseif ($kategori == 'Tim Tracer') {
            $builder = $this->db->table('detailaccount_admin da')
                ->select('da.nama_lengkap, a.email, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->like('da.nama_lengkap', $keyword);

            $result = $builder->get()->getResultArray();
        } elseif ($kategori == 'Wakil Direktur') {
            $builder = $this->db->table('detailaccount_atasan da')
                ->select('da.nama_lengkap, da.notlp, a.email, a.id as id_account')
                ->join('account a', 'a.id = da.id_account', 'left')
                ->like('da.nama_lengkap', $keyword);

            $result = $builder->get()->getResultArray();
        }

        return $this->response->setJSON($result ?: []);
    }

    // ==============================
    // Tambah kontak (multiple dari checkbox)
    // ==============================
    public function storeMultiple()
    {
        $kategori   = $this->request->getPost('kategori');
        $idAccounts = $this->request->getPost('id_account'); // array dari checkbox

        if (!$kategori || empty($idAccounts)) {
            return redirect()->back()->with('error', 'Pilih kategori dan minimal satu data!');
        }

        foreach ($idAccounts as $id_account) {
            $this->kontakModel->insert([
                'kategori'   => $kategori,
                'id_account' => $id_account
            ]);
        }

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan');
    }

    // ==============================
    // Hapus kontak
    // ==============================
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID kontak tidak valid');
        }

        $this->kontakModel->delete($id);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus');
    }

    // ==============================
    // Helper ambil kontak per kategori
    // ==============================
    private function getKontakByKategori($kategori)
    {
        $builder = $this->db->table('kontak k')
            ->join('account a', 'a.id = k.id_account', 'left');

        if ($kategori == 'Surveyor') {
            $builder->select('k.id as kontak_id, da.nama_lengkap, da.nim, da.notlp, da.tahun_kelulusan, a.email, p.nama_prodi, j.nama_jurusan')
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

    // ==============================
    // Landing Page (untuk publik)
    // ==============================
    public function landing()
    {
        return view('landingpage/kontak', [
            'wakilDirektur' => $this->getKontakByKategori('Wakil Direktur'),
            'teamTracer'    => $this->getKontakByKategori('Tim Tracer'),
            'surveyors'     => $this->getKontakByKategori('Surveyor')
        ]);
    }
}
