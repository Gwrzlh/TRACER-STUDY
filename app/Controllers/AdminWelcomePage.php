<?php

namespace App\Controllers;
use App\Models\WelcomePageModel;

class AdminWelcomePage extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function index()
    {
        // pastikan middleware/cek role admin kamu sudah diterapkan di Routes atau BaseController
        $model = new WelcomePageModel();
        $data['welcome'] = $model->first();
        // bisa kirim flashdata jika ada
        return view('adminpage/welcomePage/form', $data);
    }

    public function update()
    {
        $model = new WelcomePageModel();
        $id = $this->request->getPost('id');

        $data = [
            'title_1' => $this->request->getPost('title_1'),
            'desc_1' => $this->request->getPost('desc_1'),
            'title_2' => $this->request->getPost('title_2'),
            'desc_2' => $this->request->getPost('desc_2'),
            'youtube_url' => $this->request->getPost('youtube_url'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // upload gambar jika ada
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // opsional: hapus file lama jika ingin
            $newName = $img->getRandomName();
            $img->move(WRITEPATH . '../public/uploads/', $newName); // pastikan folder uploads writable
            $data['image_path'] = '/uploads/' . $newName;
        }

        $model->update($id, $data);
        return redirect()->to('/admin/welcome-page')->with('success', 'Konten Welcome Page berhasil diupdate.');
    }
}
