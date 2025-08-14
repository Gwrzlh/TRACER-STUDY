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
        return view('adminpage/kontak/create', [
            'wakilDirektur' => $this->getAllWakilDirektur(),
            'teamTracer'    => $this->getAllTeamTracer(),
            'surveyors'     => $this->getAllSurveyors()
        ]);
    }

    public function store()
    {
        $kategori = $this->request->getPost('kategori');
        $idAccount = $this->request->getPost('id_account');

        if (empty($idAccount)) {
            return redirect()->back()->withInput()->with('error', 'Pilih nama terlebih dahulu.');
        }

        $this->kontakModel->save([
            'kategori'   => $kategori,
            'id_account' => $idAccount
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan');
    }

    // ================== EDIT ==================
    public function edit($id)
    {
        $kontak = $this->kontakModel->find($id);

        return view('adminpage/kontak/edit', [
            'kontak'        => $kontak,
            'wakilDirektur' => $this->getAllWakilDirektur(),
            'teamTracer'    => $this->getAllTeamTracer(),
            'surveyors'     => $this->getAllSurveyors()
        ]);
    }

    public function update($id)
    {
        $kategori = $this->request->getPost('kategori');
        $idAccount = $this->request->getPost('id_account');

        $this->kontakModel->update($id, [
            'kategori'   => $kategori,
            'id_account' => $idAccount
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil diupdate');
    }

    // ================== DELETE ==================
    public function delete($id)
    {
        $this->kontakModel->delete($id);
        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus');
    }

    // ================== landingpage ==================
    // Di dalam class Kontak extends Controller
    public function landing()
    {
        return view('landingpage/kontak', [
            'wakilDirektur' => $this->getWakilDirekturKontak(),
            'teamTracer'    => $this->getTeamTracerKontak(),
            'surveyors'     => $this->getSurveyorsKontak()
        ]);
    }


    // ================== FUNGSI BANTU ==================
    private function getAllWakilDirektur()
    {
        $kaprodi = $this->db->table('detailaccount_kaprodi da')
            ->select('da.*, a.email')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->get()->getResultArray();

        $jabatan = $this->db->table('detailaccount_jabatan_lainnya da')
            ->select('da.*, a.email')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->get()->getResultArray();

        $atasan = $this->db->table('detailaccount_atasan da')
            ->select('da.*, a.email')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->get()->getResultArray();

        return array_merge($kaprodi, $jabatan, $atasan);
    }

    private function getAllTeamTracer()
    {
        return $this->getAllWakilDirektur();
    }

    private function getAllSurveyors()
    {
        return $this->db->table('detailaccount_alumni da')
            ->select('da.*, a.email, p.nama_prodi')
            ->join('account a', 'a.id = da.id_account', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->get()->getResultArray();
    }

    private function getWakilDirekturKontak()
    {
        return $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.*, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_kaprodi da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Wakil Direktur')
            ->get()->getResultArray();
    }

    private function getTeamTracerKontak()
    {
        return $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.*, a.email, k.kategori')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da', 'da.id_account = a.id', 'left')
            ->where('k.kategori', 'Tim Tracer')
            ->get()->getResultArray();
    }

    private function getSurveyorsKontak()
    {
        return $this->db->table('kontak k')
            ->select('k.id AS kontak_id, da.*, a.email, p.nama_prodi')
            ->join('account a', 'a.id = k.id_account', 'left')
            ->join('detailaccount_alumni da', 'da.id_account = a.id', 'left')
            ->join('prodi p', 'p.id = da.id_prodi', 'left')
            ->where('k.kategori', 'Surveyor')
            ->get()->getResultArray();
    }
}
