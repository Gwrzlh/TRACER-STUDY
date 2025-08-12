<?php

namespace App\Controllers;


use App\Controllers\BaseController;

class PengaturanSitus extends BaseController
{
    public function index()
    {

        $session = session();

        $data = [
            'theme'    => $session->get('theme') ?? 'light',
            'language' => $session->get('language') ?? 'id',
            'success'  => session()->getFlashdata('success'),
            'error'    => session()->getFlashdata('error')
        ];

        return view('adminpage/pengaturan_situs/situs', $data);
    }

    public function simpan()
    {
        // Pastikan requestnya POST
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to(base_url('pengaturan-situs'))
                ->with('error', 'Metode request tidak valid.');
        }

        // Ambil input
        $theme    = $this->request->getPost('theme');
        $language = $this->request->getPost('language');

        // Validasi sederhana
        if (empty($theme) || empty($language)) {
            return redirect()->back()->withInput()->with('error', 'Semua field harus diisi.');
        }

        // Simpan ke session
        $session = session();
        $session->set([
            'theme'    => $theme,
            'language' => $language
        ]);

        return redirect()
            ->to(base_url('pengaturan-situs'))
            ->with('success', 'Pengaturan situs berhasil disimpan!');
        // Memanggil view yang ada di dalam folder adminpage/pengaturansitus
        return view('adminpage/pengaturansitus/index');

    }
}
