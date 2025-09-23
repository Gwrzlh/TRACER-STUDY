<?php

namespace App\Controllers\User;

use CodeIgniter\Controller;
use Config\Database;

class KaprodiController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // ================== DASHBOARD ==================
    public function dashboard()
    {
        if (session('role_id') != 6 || session('id_surveyor')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('kaprodi/dashboard'); // Kaprodi biasa
    }

    public function supervisi()
    {
        if (session('role_id') != 6 || !session('id_surveyor')) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        return view('kaprodi/supervisi'); // Kaprodi dengan hak supervisi
    }

    // ================== MENU BARU ==================
    public function questioner()
    {
        return view('kaprodi/questioner/index');
    }

    public function akreditasi()
    {
        return view('kaprodi/akreditasi/index');
    }

    public function ami()
    {
        return view('kaprodi/ami/index');
    }

    // ================== PROFIL ==================
    public function profil()
    {
        $idAccount = session()->get('id_account'); // ambil id account dari session

        $builder = $this->db->table('detailaccount_kaprodi');
        $kaprodi = $builder->where('id_account', $idAccount)->get()->getRowArray();

        return view('kaprodi/profil/index', ['kaprodi' => $kaprodi]);
    }

    public function editProfil()
    {
        $idAccount = session()->get('id_account');

        $builder = $this->db->table('detailaccount_kaprodi');
        $kaprodi = $builder->where('id_account', $idAccount)->get()->getRowArray();

        return view('kaprodi/profil/edit', ['kaprodi' => $kaprodi]);
    }

   public function updateProfil()
{
    $idAccount = session()->get('id_account');

    $data = [];

    // handle upload foto
    $file = $this->request->getFile('foto');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = time() . '_' . $file->getRandomName();
        $file->move(FCPATH . 'uploads/kaprodi', $newName);
        $data['foto'] = $newName;

        // update session biar sidebar langsung berubah
        session()->set('foto', $newName);
    }

    if (!empty($data)) {
        $builder = $this->db->table('detailaccount_kaprodi');
        $builder->where('id_account', $idAccount)->update($data);
    }

    return redirect()->to('/kaprodi/profil')->with('success', 'Foto profil berhasil diperbarui.');
}


}
