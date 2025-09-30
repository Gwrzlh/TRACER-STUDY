<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LandingPage\WelcomePageModel;

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
            'title_1'     => $this->request->getPost('title_1'),
            'desc_1'      => $this->request->getPost('desc_1'),
            'title_2'     => $this->request->getPost('title_2'),
            'desc_2'      => $this->request->getPost('desc_2'),
            'title_3'     => $this->request->getPost('title_3'),
            'desc_3'      => $this->request->getPost('desc_3'),
            'youtube_url' => $this->request->getPost('youtube_url'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        // upload gambar pertama jika ada
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // opsional: hapus file lama jika ingin
            $newName = $img->getRandomName();
            $img->move(WRITEPATH . '../public/uploads/', $newName); // pastikan folder uploads writable
            $data['image_path'] = '/uploads/' . $newName;
        }

        // upload gambar kedua jika ada
        $img2 = $this->request->getFile('image_2');
        if ($img2 && $img2->isValid() && !$img2->hasMoved()) {
            $newName2 = $img2->getRandomName();
            $img2->move(WRITEPATH . '../public/uploads/', $newName2); // pastikan folder uploads writable
            $data['image_path_2'] = '/uploads/' . $newName2;
        }

        $model->update($id, $data);
        return redirect()->to('/admin/welcome-page')->with('success', 'Konten Welcome Page berhasil diupdate.');
    }
}
