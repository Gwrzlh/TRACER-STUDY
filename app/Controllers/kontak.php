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

    // Ambil semua nama untuk kategori Wakil Direktur / Tim Tracer
    private function getWakilDirekturTeamTracer()
    {
        $kaprodi = $this->db->table('detailaccount_kaprodi')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_kaprodi.id_account')
            ->get()->getResultArray();

        $jabatanLainnya = $this->db->table('detailaccount_jabatan_lainnya')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_jabatan_lainnya.id_account')
            ->get()->getResultArray();

        $atasan = $this->db->table('detailaccount_atasan')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_atasan.id_account')
            ->get()->getResultArray();

        return array_merge($kaprodi, $jabatanLainnya, $atasan);
    }

    // Ambil semua nama untuk kategori Surveyor
    private function getSurveyors()
    {
        return $this->db->table('detailaccount_alumni')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_alumni.id_account')
            ->get()->getResultArray();
    }

    public function index()
    {
        // Wakil Direktur + Team Tracer (kaprodi, jabatan lainnya, atasan)
        $nonSurveyor = $this->db->table('kontak')
            ->select('kontak.id, kontak.kategori, account.email, 
                  IFNULL(da_kaprodi.nama_lengkap, 
                  IFNULL(da_jabatan.nama_lengkap, da_atasan.nama_lengkap)) AS nama_lengkap,
                  kontak.alamat')
            ->join('account', 'account.id = kontak.id_account')
            ->join('detailaccount_kaprodi da_kaprodi', 'da_kaprodi.id_account = account.id', 'left')
            ->join('detailaccount_jabatan_lainnya da_jabatan', 'da_jabatan.id_account = account.id', 'left')
            ->join('detailaccount_atasan da_atasan', 'da_atasan.id_account = account.id', 'left')
            ->where('kontak.kategori !=', 'Surveyor')
            ->get()->getResultArray();

        // Surveyor (alumni)
        $surveyors = $this->db->table('kontak')
            ->select('
            kontak.id, 
            prodi.nama_prodi, 
            da_alumni.nama_lengkap, 
            da_alumni.tahun_kelulusan, 
            account.email, 
            da_alumni.notlp
        ')
            ->join('account', 'account.id = kontak.id_account')
            ->join('detailaccount_alumni da_alumni', 'da_alumni.id_account = account.id', 'left')
            ->join('prodi', 'prodi.id = da_alumni.id_prodi', 'left')
            ->where('kontak.kategori', 'Surveyor')
            ->get()->getResultArray();

        return view('adminpage/kontak/index', [
            'nonSurveyor' => $nonSurveyor,
            'surveyors'   => $surveyors
        ]);
    }
    public function create()
    {
        // Sumber data untuk Wakil Direktur & Team Tracer (sama)
        $gabunganNonSurveyor = array_merge(
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

        // Data surveyor (alumni)
        $surveyors = $this->db->table('detailaccount_alumni')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_alumni.id_account')
            ->get()->getResultArray();

        return view('adminpage/kontak/create', [
            'wakilDirektur' => $gabunganNonSurveyor,
            'teamTracer'    => $gabunganNonSurveyor,
            'surveyors'     => $surveyors
        ]);
    }

    public function store()
    {
        $kategori = $this->request->getPost('kategori');
        $idAccount = $this->request->getPost('id_account');
        $alamat = ($kategori === 'Surveyor') ? null : $this->request->getPost('alamat');

        if (empty($idAccount)) {
            return redirect()->back()->withInput()->with('error', 'Pilih nama terlebih dahulu.');
        }

        $this->kontakModel->save([
            'kategori'   => $kategori,
            'id_account' => $idAccount,
            'alamat'     => $alamat
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil ditambahkan');
    }


    public function edit($id)
    {
        $kontak = $this->kontakModel->find($id);

        // Sumber data untuk Wakil Direktur & Team Tracer
        $gabunganNonSurveyor = array_merge(
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

        // Data surveyor (alumni)
        $surveyors = $this->db->table('detailaccount_alumni')
            ->select('account.id, nama_lengkap')
            ->join('account', 'account.id = detailaccount_alumni.id_account')
            ->get()->getResultArray();

        return view('adminpage/kontak/edit', [
            'kontak'        => $kontak,
            'wakilDirektur' => $gabunganNonSurveyor,
            'teamTracer'    => $gabunganNonSurveyor,
            'surveyors'     => $surveyors
        ]);
    }

    public function update($id)
    {
        $kategori = $this->request->getPost('kategori');

        if ($kategori === 'Surveyor') {
            $idAccount = $this->request->getPost('id_account_surveyor');
            $alamat = null;
        } elseif ($kategori === 'Wakil Direktur') {
            $idAccount = $this->request->getPost('id_account_wakil');
            $alamat = $this->request->getPost('alamat');
        } else { // Team Tracer
            $idAccount = $this->request->getPost('id_account_team');
            $alamat = $this->request->getPost('alamat');
        }

        $this->kontakModel->update($id, [
            'kategori'   => $kategori,
            'id_account' => $idAccount,
            'alamat'     => $alamat
        ]);

        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil diupdate');
    }



    public function delete($id)
    {
        $this->kontakModel->delete($id);
        return redirect()->to('/admin/kontak')->with('success', 'Kontak berhasil dihsapus');
    }

    // ----------------- LANDING PAGE -----------------
    public function landing()
    {
        // Ambil kontak kategori non-surveyor (Wakil Direktur & Team Tracer)
        $nonSurveyor = $this->db->table('kontak')
            ->select('kontak.kategori, kontak.alamat, account.email, 
              IFNULL(da_kaprodi.nama_lengkap, 
              IFNULL(da_jabatan.nama_lengkap, 
              IFNULL(da_atasan.nama_lengkap, NULL))) AS nama_lengkap,
              IFNULL(da_kaprodi.notlp, 
              IFNULL(da_jabatan.notlp, 
              IFNULL(da_atasan.notlp, NULL))) AS notlp')
            ->join('account', 'account.id = kontak.id_account')
            ->join('detailaccount_kaprodi da_kaprodi', 'da_kaprodi.id_account = account.id', 'left')
            ->join('detailaccount_jabatan_lainnya da_jabatan', 'da_jabatan.id_account = account.id', 'left')
            ->join('detailaccount_atasan da_atasan', 'da_atasan.id_account = account.id', 'left')
            ->where('kontak.kategori !=', 'Surveyor')
            ->get()->getResultArray();

        // Ambil data Surveyor
        $surveyors = $this->db->table('detailaccount_alumni da')
            ->select('
        prodi.nama_prodi,
        da.nama_lengkap,
        da.tahun_kelulusan,
        account.email,
        da.notlp
    ')
            ->join('account', 'account.id = da.id_account')
            ->join('prodi', 'prodi.id = da.id_prodi', 'left')
            ->orderBy('da.nama_lengkap', 'ASC')
            ->get()
            ->getResultArray();

        return view('LandingPage/kontak', [
            'nonSurveyor'  => $nonSurveyor,
            'surveyors'    => $surveyors
        ]);
    }
}
