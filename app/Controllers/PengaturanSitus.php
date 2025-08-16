<?php

namespace App\Controllers;


use App\Controllers\BaseController;

class PengaturanSitus extends BaseController
{
    public function index()
    {

        $session = session();

$data = [
        'theme'      => session()->get('theme') ?? 'light',
        'per_page'   => session()->get('per_page') ?? 10,
        'view_mode'  => session()->get('view_mode') ?? 'table',
    ];

    return view('adminpage/pengaturansitus/index', $data);

    }

   public function simpan()
{
    $theme = $this->request->getPost('theme');
    $per_page = (int) $this->request->getPost('per_page');
    $view_mode = $this->request->getPost('view_mode');

    // Simpan ke session
    session()->set([
        'theme' => $theme,
        'per_page' => $per_page,
        'view_mode' => $view_mode
    ]);

    return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
}

}
