<?php

namespace App\Controllers;

use App\Models\KontakModel;
use App\Models\Prodi;
use App\Models\JurusanModel;

class KontakController extends BaseController
{
    protected $kontakModel;

    public function __construct()
    {
        $this->kontakModel = new KontakModel();
    }

    // === FRONTEND: Halaman kontak publik ===
    public function public()
    {
        $kontakAktif = $this->kontakModel
            ->select('kontak.*, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('prodi', 'prodi.id = kontak.id_prodi', 'left')
            ->join('jurusan', 'jurusan.id = kontak.id_jurusan', 'left')
            ->where('kontak.aktif', 1)
            ->orderBy('tipe_kontak', 'asc')
            ->orderBy('urutan', 'asc')
            ->findAll();

        // Kelompokkan berdasarkan tipe_kontak
        $grouped = [
            'deskripsi_header' => [],
            'deskripsi_tim'    => [],
            'surveyor_info'    => [],
            'surveyor'         => [],
            'coordinator'      => [],
            'team'             => [],
            'directorate'      => [],
            'address'          => [],
        ];

        foreach ($kontakAktif as $k) {
            $grouped[$k['tipe_kontak']][] = $k;
        }

        return view('pages/kontak_public', [
            'groupedKontak' => $grouped,
        ]);
    }

    // === ADMIN: Tampilkan semua kontak ===
    public function index()
    {
        $data['kontaks'] = $this->kontakModel
            ->select('kontak.*, prodi.nama_prodi, jurusan.nama_jurusan')
            ->join('prodi', 'prodi.id = kontak.id_prodi', 'left')
            ->join('jurusan', 'jurusan.id = kontak.id_jurusan', 'left')
            ->orderBy('kontak.urutan', 'ASC')
            ->findAll();

        return view('adminpage/kontak/index', $data);
    }

    // === ADMIN: Form tambah kontak ===
    public function create()
    {
        $data['prodiList'] = (new Prodi())->findAll();
        $data['jurusanList'] = (new JurusanModel())->findAll();

        return view('adminpage/kontak/create', $data);
    }

    // === ADMIN: Simpan kontak baru ===
    public function store()
    {
        $tipe = $this->request->getPost('tipe_kontak');

        $data = [
            'nama'         => $this->request->getPost('nama'),
            'posisi'       => $this->request->getPost('posisi'),
            'kualifikasi'  => $this->request->getPost('kualifikasi'),
            'tipe_kontak'  => $tipe,
            'urutan'       => $this->request->getPost('urutan'),
            'aktif'        => $this->request->getPost('aktif'),
            'kontak'       => $this->request->getPost('kontak'),
            'id_prodi'     => $tipe === 'surveyor' ? $this->request->getPost('id_prodi') : null,
            'id_jurusan'   => $tipe === 'coordinator' ? $this->request->getPost('id_jurusan') : null,
        ];

        $this->kontakModel->save($data);

        return redirect()->to('/admin/kontak')->with('success', 'Data kontak berhasil ditambahkan.');
    }

    // === ADMIN: Form edit kontak ===
    public function edit($id)
    {
        $data['kontak'] = $this->kontakModel->find($id);
        $data['prodiList'] = (new Prodi())->findAll();
        $data['jurusanList'] = (new JurusanModel())->findAll();

        return view('adminpage/kontak/edit', $data);
    }

    // === ADMIN: Simpan perubahan kontak ===
    public function update($id)
    {
        $tipe = $this->request->getPost('tipe_kontak');

        $data = [
            'nama'         => $this->request->getPost('nama'),
            'posisi'       => $this->request->getPost('posisi'),
            'kualifikasi'  => $this->request->getPost('kualifikasi'),
            'tipe_kontak'  => $tipe,
            'urutan'       => $this->request->getPost('urutan'),
            'aktif'        => $this->request->getPost('aktif'),
            'kontak'       => $this->request->getPost('kontak'),
            'id_prodi'     => $tipe === 'surveyor' ? $this->request->getPost('id_prodi') : null,
            'id_jurusan'   => $tipe === 'coordinator' ? $this->request->getPost('id_jurusan') : null,
        ];

        $this->kontakModel->update($id, $data);

        return redirect()->to('/admin/kontak')->with('success', 'Data kontak berhasil diubah.');
    }

    // === ADMIN: Hapus kontak ===
    public function delete($id)
    {
        $this->kontakModel->delete($id);
        return redirect()->to('/admin/kontak')->with('success', 'Data kontak berhasil dihapus.');
    }
}
