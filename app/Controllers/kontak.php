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

    // Ambil semua data untuk Wakil Direktur (gabungan 3 tabel)
    private function getWakilDirektur()
    {
        return array_merge(
            $this->db->table('detailaccount_kaprodi')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_kaprodi.id_account')
                ->get()->getResultArray(),
            $this->db->table('detailaccount_jabatan_lainnya')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_jabatan_lainnya.id_account')
                ->get()->getResultArray(),
            $this->db->table('detailaccount_atasan')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_atasan.id_account')
                ->get()->getResultArray()
        );
    }

    // Ambil semua data untuk Team Tracer (gabungan 3 tabel)
    private function getTeamTracer()
    {
        return array_merge(
            $this->db->table('detailaccount_kaprodi')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_kaprodi.id_account')
                ->get()->getResultArray(),
            $this->db->table('detailaccount_jabatan_lainnya')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_jabatan_lainnya.id_account')
                ->get()->getResultArray(),
            $this->db->table('detailaccount_atasan')
                ->select('account.id, nama_lengkap')
                ->join('account', 'account.id = detailaccount_atasan.id_account')
                ->get()->getResultArray()
        );
    }

    // Ambil data Surveyor
    private function getSurveyors()
    {
        return $this->db->table('detailaccount_alumni da')
            ->select('account.id, da.nama_lengkap, da.notlp, account.email, prodi.nama_prodi, da.tahun_kelulusan')
            ->join('account', 'account.id = da.id_account')
            ->join('prodi', 'prodi.id = da.id_prodi', 'left')
            ->get()->getResultArray();
    }

    // ---------------- INDEX ----------------
    public function index()
    {
        // Kontak Wakil Direktur
        $wakilDirektur = $this->db->table('kontak')
            ->select('kontak.id, kontak.kategori, account.id as account_id, da_kaprodi.nama_lengkap')
            ->join('account', 'account.id = kontak.id_account', 'left')
            ->join('detailaccount_kaprodi da_kaprodi', 'da_kaprodi.id_account = account.id', 'left')
            ->where('kontak.kategori', 'Wakil Direktur')
            ->get()->getResultArray();

        // Kontak Team Tracer
        $teamTracer = $this->db->table('kontak')
            ->select('kontak.id, kontak.kategori, account.id as account_id, da_jabatan.nama_lengkap')
            ->join('account', 'account.id = kontak.id_account', 'left')
            ->join('detailaccount_jabatan_lainnya da_jabatan', 'da_jabatan.id_account = account.id', 'left')
            ->where('kontak.kategori', 'Tim Tracer')
            ->get()->getResultArray();

        // Kontak Surveyor
        $surveyors = $this->db->table('kontak')
            ->select('kontak.id, da.nama_lengkap, da.notlp, account.email, prodi.nama_prodi, da.tahun_kelulusan')
            ->join('account', 'account.id = kontak.id_account', 'left')
            ->join('detailaccount_alumni da', 'da.id_account = account.id', 'left')
            ->join('prodi', 'prodi.id = da.id_prodi', 'left')
            ->where('kontak.kategori', 'Surveyor')
            ->get()->getResultArray();

        return view('adminpage/kontak/index', [
            'wakilDirektur' => $wakilDirektur,
            'teamTracer'    => $teamTracer,
            'surveyors'     => $surveyors
        ]);
    }

    // ---------------- CREATE ----------------
    public function create()
    {
        return view('adminpage/kontak/create', [
            'wakilDirektur' => $this->getWakilDirektur(),
            'teamTracer'    => $this->getTeamTracer(),
            'surveyors'     => $this->getSurveyors()
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

    // ---------------- EDIT ----------------
    public function edit($id)
    {
        $kontak = $this->kontakModel->find($id);

        return view('adminpage/kontak/edit', [
            'kontak'        => $kontak,
            'wakilDirektur' => $this->getWakilDirektur(),
            'teamTracer'    => $this->getTeamTracer(),
            'surveyors'     => $this->getSurveyors()
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

    // ---------------- DELETE ----------------
    public function delete($id)
    {
        $this->kontakModel->delete($id);
        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihapus');
    }

    // ---------------- LANDING PAGE ----------------
    public function landing()
    {
        // ----------------- WAKIL DIREKTUR -----------------
        $wakilDirektur = array_merge(
            $this->db->table('kontak')
                ->select('da_kaprodi.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_kaprodi da_kaprodi', 'da_kaprodi.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Wakil Direktur')
                ->get()->getResultArray(),

            $this->db->table('kontak')
                ->select('da_jabatan.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_jabatan_lainnya da_jabatan', 'da_jabatan.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Wakil Direktur')
                ->get()->getResultArray(),

            $this->db->table('kontak')
                ->select('da_atasan.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_atasan da_atasan', 'da_atasan.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Wakil Direktur')
                ->get()->getResultArray()
        );

        // ----------------- TEAM TRACER -----------------
        $teamTracer = array_merge(
            $this->db->table('kontak')
                ->select('da_kaprodi.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_kaprodi da_kaprodi', 'da_kaprodi.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Tim Tracer')
                ->get()->getResultArray(),

            $this->db->table('kontak')
                ->select('da_jabatan.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_jabatan_lainnya da_jabatan', 'da_jabatan.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Tim Tracer')
                ->get()->getResultArray(),

            $this->db->table('kontak')
                ->select('da_atasan.nama_lengkap as nama_lengkap')
                ->join('account', 'account.id = kontak.id_account')
                ->join('detailaccount_atasan da_atasan', 'da_atasan.id_account = account.id', 'left')
                ->where('kontak.kategori', 'Tim Tracer')
                ->get()->getResultArray()
        );

        // ----------------- SURVEYOR -----------------
        $surveyors = $this->db->table('kontak')
            ->select('kontak.id, da.nama_lengkap, da.notlp, account.email, prodi.nama_prodi, da.tahun_kelulusan')
            ->join('account', 'account.id = kontak.id_account')
            ->join('detailaccount_alumni da', 'da.id_account = account.id', 'left')
            ->join('prodi', 'prodi.id = da.id_prodi', 'left')
            ->where('kontak.kategori', 'Surveyor')
            ->get()->getResultArray();

        return view('LandingPage/kontak', [
            'wakilDirektur' => $wakilDirektur,
            'teamTracer'    => $teamTracer,
            'surveyors'     => $surveyors
        ]);
    }
}
