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

    // ================== INDEX ==================
    public function index()
    {
        return view('adminpage/kontak/index', [
            'wakilDirektur' => $this->getWakilDirekturKontak(),
            'teamTracer'    => $this->getTeamTracerKontak(),
            'surveyors'     => $this->getSurveyorsKontak()
        ]);
    }

    // ================== CREATE ==================
    public function create()
    {
        return view('adminpage/kontak/create');
    }

    // AJAX - Ambil data berdasarkan kategori, exclude yang sudah ada
    public function getByKategori($kategori)
    {
        $result = [];

        switch ($kategori) {
            case 'Wakil Direktur':
                $result = $this->getAllWakilDirektur(true);
                break;
            case 'Tim Tracer':
                $result = $this->getAllTeamTracer(true);
                break;
            case 'Surveyor':
                $result = $this->getAllSurveyors(true);
                break;
        }

        return $this->response->setJSON($result);
    }

    public function store()
    {
        $kategori = $this->request->getPost('kategori');
        $idAccounts = $this->request->getPost('id_account');

        if (empty($idAccounts) || !is_array($idAccounts)) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu kontak.');
        }

        $dataToInsert = [];
        foreach ($idAccounts as $id) {
            // Cek apakah sudah ada (anti-duplikat)
            $exists = $this->kontakModel
                ->where('kategori', $kategori)
                ->where('id_account', $id)
                ->first();

            if (!$exists) {
                $dataToInsert[] = [
                    'kategori'   => $kategori,
                    'id_account' => $id
                ];
            }
        }

        if (!empty($dataToInsert)) {
            $this->kontakModel->insertBatch($dataToInsert);
        }

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan');
    }
    // ================== EDIT ==================
    public function edit($id)
    {
        // Ambil data kontak yang ingin diedit
        $kontak = $this->kontakModel->find($id);

        if (!$kontak) {
            return redirect()->to('/admin/kontak')->with('error', 'Kontak tidak ditemukan');
        }

        // Ambil semua account sesuai kategori agar dropdown terisi
        $accounts = [];
        switch ($kontak['kategori']) {
            case 'Wakil Direktur':
                $accounts = $this->getAllWakilDirektur();
                break;
            case 'Tim Tracer':
                $accounts = $this->getAllTeamTracer();
                break;
            case 'Surveyor':
                $accounts = $this->getAllSurveyors();
                break;
        }

        return view('adminpage/kontak/edit', [
            'kontak'   => $kontak,
            'accounts' => $accounts
        ]);
    }

    // ================== UPDATE ==================
    public function update($id)
    {
        $kategori = $this->request->getPost('kategori');
        $id_account = $this->request->getPost('id_account');

        if (!$kategori || !$id_account) {
            return redirect()->back()->withInput()->with('error', 'Kategori dan nama harus dipilih.');
        }

        // Update data kontak di database
        $this->kontakModel->update($id, [
            'kategori'   => $kategori,
            'id_account' => $id_account
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil diperbarui');
    }
    // ================== LANDING PAGE ==================
    public function landing()
    {
        // ================== WAKIL DIREKTUR ==================
        $kaprodi = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_kaprodi da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        $jabatan = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        $atasan = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_atasan da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        $wakilDirektur = array_merge($kaprodi, $jabatan, $atasan);

        // ================== TEAM TRACER ==================
        $kaprodiTT = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_kaprodi da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        $jabatanTT = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        $atasanTT = $this->db->table('kontak k')
            ->select('da.nama_lengkap')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_atasan da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        $teamTracer = array_merge($kaprodiTT, $jabatanTT, $atasanTT);

        // ================== SURVEYOR ==================
        $surveyors = $this->db->table('kontak k')
            ->select('da.nama_lengkap, da.notlp, a.email, p.nama_prodi')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_alumni da', 'da.id_account = a.id', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->where('k.kategori', 'Surveyor')
            ->get()->getResultArray();

        return view('LandingPage/kontak', [
            'wakilDirektur' => $wakilDirektur,
            'teamTracer'    => $teamTracer,
            'surveyors'     => $surveyors
        ]);
    }



    // ================== GET ACCOUNTS BY KATEGORI (AJAX) ==================
    public function getAccountsByKategori($kategori)
    {
        $result = [];

        switch ($kategori) {
            case 'Wakil Direktur':
                $result = $this->getAllWakilDirektur();
                break;
            case 'Tim Tracer':
                $result = $this->getAllTeamTracer();
                break;
            case 'Surveyor':
                $result = $this->getAllSurveyors();
                break;
        }

        return $this->response->setJSON($result);
    }



    // ================== DELETE ==================
    public function delete($id)
    {
        $this->kontakModel->delete($id);
        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus');
    }

    public function deleteByKategori($kategori)
    {
        $this->kontakModel->where('kategori', $kategori)->delete();
        return redirect()->to('/admin/kontak')->with('success', 'Semua kontak kategori ' . $kategori . ' berhasil dihapus');
    }

    // ================== FUNGSI BANTU ==================
    private function getAllWakilDirektur($excludeAdded = false)
    {
        $builder = $this->db->table('detailaccount_kaprodi da')
            ->select('da.*, a.email')
            ->join('account a', 'a.id = da.id_account', 'left');

        if ($excludeAdded) {
            $builder->whereNotIn('a.id', function ($sub) {
                $sub->select('id_account')->from('kontak')->where('kategori', 'Wakil Direktur');
            });
        }

        $kaprodi = $builder->get()->getResultArray();

        $builder2 = $this->db->table('detailaccount_jabatan_lainnya da')
            ->select('da.id_account, da.nama_lengkap, da.notlp, a.email')
            ->join('account a', 'a.id = da.id_account', 'left');


        if ($excludeAdded) {
            $builder2->whereNotIn('a.id', function ($sub) {
                $sub->select('id_account')->from('kontak')->where('kategori', 'Wakil Direktur');
            });
        }

        $jabatan = $builder2->get()->getResultArray();

        $builder3 = $this->db->table('detailaccount_atasan da')
            ->select('da.id_account, da.nama_lengkap, da.notlp, a.email')
            ->join('account a', 'a.id = da.id_account', 'left');


        if ($excludeAdded) {
            $builder3->whereNotIn('a.id', function ($sub) {
                $sub->select('id_account')->from('kontak')->where('kategori', 'Wakil Direktur');
            });
        }

        $atasan = $builder3->get()->getResultArray();

        return array_merge($kaprodi, $jabatan, $atasan);
    }

    private function getAllTeamTracer($excludeAdded = false)
    {
        // Kalau logikanya sama dengan Wakil Direktur, bisa langsung reuse
        return $this->getAllWakilDirektur($excludeAdded);
    }

    private function getAllSurveyors($excludeAdded = false)
    {
        $builder = $this->db->table('detailaccount_alumni da')
            ->select('da.*, a.email, p.nama_prodi')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left');

        if ($excludeAdded) {
            $builder->whereNotIn('a.id', function ($sub) {
                $sub->select('id_account')->from('kontak')->where('kategori', 'Surveyor');
            });
        }

        return $builder->get()->getResultArray();
    }

    private function getWakilDirekturKontak()
    {
        // 1. Kaprodi
        $kaprodi = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_kaprodi da', 'da.id_account = a.id', 'inner') // pakai inner join supaya ada data
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        // 2. Jabatan Lainnya
        $jabatan = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da', 'da.id_account = a.id', 'inner')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        // 3. Atasan
        $atasan = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_atasan da', 'da.id_account = a.id', 'inner')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        return array_merge($kaprodi, $jabatan, $atasan);
    }

    private function getTeamTracerKontak()
    {
        // 1. Kaprodi (jika ada yang masuk kategori Tim Tracer)
        $kaprodi = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_kaprodi da', 'da.id_account = a.id', 'inner') // inner join supaya ada data
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        // 2. Jabatan Lainnya
        $jabatan = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da', 'da.id_account = a.id', 'inner')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        // 3. Atasan
        $atasan = $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_atasan da', 'da.id_account = a.id', 'inner')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        return array_merge($kaprodi, $jabatan, $atasan);
    }


    private function getSurveyorsKontak()
    {
        return $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.nama_lengkap, da.nim, da.notlp, a.email, p.nama_prodi, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_alumni da', 'da.id_account = a.id', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->where('k.kategori', 'Surveyor')
            ->get()->getResultArray();
    }
}
