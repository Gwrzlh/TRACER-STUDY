<?php

namespace App\Controllers;

use App\Models\TentangModel;

class Tentang extends BaseController
{
    protected $tentangModel;

    public function __construct()
    {
        $this->tentangModel = new TentangModel();
    }

    // Halaman publik (Landing Page)
    public function index()
    {
        $data['tentang'] = $this->tentangModel->first() ?? [
            'judul' => 'Judul belum diisi',
            'isi' => 'Konten belum tersedia...',
            'judul2' => '',
            'isi2' => '',
            'judul3' => '',
            'isi3' => '',
            'gambar' => null,
            'gambar2' => null,
        ];

        return view('LandingPage/tentang', $data);
    }

    // Halaman publik Event (judul3, isi3, gambar2)
    public function event()
    {
        $data['tentang'] = $this->tentangModel->first();
        return view('LandingPage/event', $data);
    }

    // Admin: form edit
   public function edit()
{
    $data['tentang'] = $this->tentangModel->first();

    $eventHistoryModel = new \App\Models\EventHistoryModel();
    $data['historyEvents'] = $eventHistoryModel->orderBy('created_at', 'DESC')->findAll();

    return view('adminpage/tentang/edit', $data);
}


   // Admin: simpan perubahan
public function update()
{
    $id      = $this->request->getPost('id');
    $judul   = $this->request->getPost('judul');
    $isi     = $this->request->getPost('isi');
    $judul2  = $this->request->getPost('judul2');
    $isi2    = $this->request->getPost('isi2');
    $judul3  = $this->request->getPost('judul3');
    $isi3    = $this->request->getPost('isi3');

    // Upload gambar 1
    $gambarFile = $this->request->getFile('gambar');
    $gambarName = null;
    if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
        $gambarName = $gambarFile->getRandomName();
        $gambarFile->move('uploads', $gambarName);
    }

    // Upload gambar 2
    $gambarFile2 = $this->request->getFile('gambar2');
    $gambarName2 = null;
    if ($gambarFile2 && $gambarFile2->isValid() && !$gambarFile2->hasMoved()) {
        $gambarName2 = $gambarFile2->getRandomName();
        $gambarFile2->move('uploads', $gambarName2);
    }

    $dataUpdate = [
        'judul'   => $judul,
        'isi'     => $isi,
        'judul2'  => $judul2,
        'isi2'    => $isi2,
        'judul3'  => $judul3,
        'isi3'    => $isi3,
    ];

    if ($gambarName) {
        $dataUpdate['gambar'] = $gambarName;
    }
    if ($gambarName2) {
        $dataUpdate['gambar2'] = $gambarName2;
    }

    // Update tabel tentang (hanya 1 record)
    $this->tentangModel->update($id, $dataUpdate);

    // Simpan juga ke tabel history (hanya data event)
    $eventHistoryModel = new \App\Models\EventHistoryModel();
    $eventData = [
        'judul3'  => $judul3,
        'isi3'    => $isi3,
        'gambar2' => $gambarName2 ?? $this->tentangModel->find($id)['gambar2'] ?? null,
    ];
    $eventHistoryModel->insert($eventData);

    return redirect()->to('admin/tentang/edit')->with('success', 'Data berhasil diupdate & history event ditambahkan.');
}

}
