<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailaccountAlumni;
use App\Models\PesanModel; // <- tambahin model pesan
use App\Models\AlumniModel;

class AlumniController extends BaseController
{
    protected $pesanModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    public function dashboard()
    {
        return view('alumni/dashboard');
    }

    public function questioner()
    {
        return view('alumni/questioner/index');
    }

   

    public function questionersurveyor()

    {
        return view('alumni/alumnisurveyor/questioner/index');
    }

    public function profil()
    {
        $session = session();
        $alumniModel = new AlumniModel();

        $idAccount = $session->get('id_account');

        // Ambil data alumni dari database
        $alumni = $alumniModel->where('id_account', $idAccount)->first();

        // Kalau tidak ada di DB, fallback dari session biar tidak error
        if (!$alumni) {
            $alumni = [
                'nama_lengkap' => $session->get('nama_lengkap'),
                'nim'          => '-', 
                'nama_prodi'   => '-', 
                'foto'         => null
            ];
        }

        return view('alumni/profil/index', [
            'alumni' => (object) $alumni
        ]);
    }


   public function editProfil()
{
    $id = session()->get('id_account');
    $alumniModel = new AlumniModel();
    $alumni = $alumniModel->where('id_account', $id)->first();

    return view('alumni/profil/edit', [
    'alumni' => (object) $alumni   // <-- ubah array jadi object
]);

}



    public function supervisi()
    {
        return view('alumni/alumnisurveyor/supervisi');

    }

    public function lihatTeman()
    {
        $alumniModel  = new \App\Models\DetailaccountAlumni();
        $jurusanModel = new \App\Models\JurusanModel();
        $prodiModel   = new \App\Models\Prodi();

        $currentAlumni = $alumniModel
            ->where('id_account', session('id'))
            ->first();

        if (!$currentAlumni) {
            return redirect()->back()->with('error', 'Data alumni tidak ditemukan.');
        }

        $jurusanNama = $jurusanModel->find($currentAlumni['id_jurusan'])['nama_jurusan'] ?? '-';
        $prodiNama   = $prodiModel->find($currentAlumni['id_prodi'])['nama_prodi'] ?? '-';

        $teman = $alumniModel
            ->where('id_jurusan', $currentAlumni['id_jurusan'])
            ->where('id_prodi', $currentAlumni['id_prodi'])
            ->where('id_account !=', session('id'))
            ->findAll();



        foreach ($teman as &$t) {
            $statuses = ['Finish', 'Ongoing', 'Belum Mengisi'];
            $t['status'] = $statuses[array_rand($statuses)];
        }
        unset($t);


        $data = [
            'teman'   => $teman,
            'jurusan' => $jurusanNama,
            'prodi'   => $prodiNama,
        ];

        return view('alumni/alumnisurveyor/lihat_teman', $data);
    }

    // =============================
    // 🔔 FITUR NOTIFIKASI PESAN
    // =============================

    // 1. Kirim pesan otomatis (dipakai alumni surveyor)
    public function kirimPesan($idPenerima)
    {
        $idPengirim = session()->get('id'); // alumni surveyor yg login

        $pesanOtomatis = "Halo, ada pesan baru dari surveyor terkait tracer study.";

        $insert = $this->pesanModel->insert([
            'id_pengirim' => $idPengirim,
            'id_penerima' => $idPenerima,
            'pesan'       => $pesanOtomatis,
            'status'      => 'terkirim'
        ]);

        if ($insert) {
            return redirect()->back()->with('success', '✅ Pesan berhasil dikirim!');
        } else {
            return redirect()->back()->with('error', '❌ Pesan gagal dikirim.');
        }
    }

    // 2. Ambil notifikasi (buat icon lonceng)
    // Tampilkan halaman notifikasi
    public function notifikasi()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('alumni/notifikasi', ['pesan' => $pesan]);
    }

    // Ambil data jumlah notif via AJAX
    public function getNotifCount()
    {
        $idAlumni = session()->get('id');
        $pesan = $this->pesanModel
            ->where('id_penerima', $idAlumni)
            ->where('status', 'terkirim')
            ->findAll();

        return $this->response->setJSON(['jumlah' => count($pesan)]);
    }


    // 3. Tandai sudah dibaca
    public function tandaiDibaca($id_pesan)
    {
        $this->pesanModel->update($id_pesan, ['status' => 'dibaca']);
        return redirect()->back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
    public function hapusNotifikasi($id)
    {
        $pesanModel = new \App\Models\PesanModel();
        $pesan = $pesanModel->find($id);

        if ($pesan && $pesan['id_penerima'] == session()->get('id')) {
            $pesanModel->delete($id);
            return redirect()->to('/alumni/notifikasi')->with('success', 'Pesan berhasil dihapus.');
        }

        return redirect()->to('/alumni/notifikasi')->with('error', 'Pesan tidak ditemukan atau bukan milik Anda.');
    }
    public function pesan($idPenerima)
    {
        // ambil data penerima
        $db = db_connect();
        $penerima = $db->table('account')->where('id', $idPenerima)->get()->getRowArray();

        if (!$penerima) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("User tidak ditemukan");
        }

        return view('alumni/pesan_form', [
            'penerima' => $penerima
        ]);
    }

    public function update()
{
    $id_account = session()->get('id_account'); 
    $alumniModel = new AlumniModel();

    $alumni = $alumniModel->where('id_account', $id_account)->first();
    if (!$alumni) {
        return redirect()->to(base_url('alumni/profil'))
            ->with('error', 'Data alumni tidak ditemukan');
    }

    $data = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'alamat'       => $this->request->getPost('alamat'),
    ];

    $foto = $this->request->getFile('foto');
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {
        $newName = $foto->getRandomName();
        $foto->move('uploads', $newName);
        $data['foto'] = $newName;
    }

    $alumniModel->where('id_account', $id_account)->set($data)->update();

    return redirect()->to(base_url('alumni/profil'))->with('success', 'Profil berhasil diupdate');
}

public function updateProfil()
{
    $session = session();
    $idAccount = $session->get('id_account');

    if (!$idAccount) {
        return redirect()->to('/login')->with('error', 'Silakan login kembali.');
    }

    $alumniModel = new \App\Models\AlumniModel();

    // Ambil data dari form
    $data = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'alamat'       => $this->request->getPost('alamat'),
    ];

    // Upload foto jika ada
    $foto = $this->request->getFile('foto');
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {
        $newName = $foto->getRandomName();
        $foto->move(FCPATH . 'uploads', $newName);
        $data['foto'] = $newName;
    }

    // Update data alumni
    $alumniModel->where('id_account', $idAccount)->set($data)->update();

    // ✅ update session supaya sidebar ikut berubah langsung
    if (isset($data['nama_lengkap']) && !empty($data['nama_lengkap'])) {
        $session->set('nama_lengkap', $data['nama_lengkap']);
    }
    if (isset($data['foto'])) {
        $session->set('foto', $data['foto']);
    }

    return redirect()->to(base_url('alumni/profil'))->with('success', 'Profil berhasil diperbarui.');
}





}
