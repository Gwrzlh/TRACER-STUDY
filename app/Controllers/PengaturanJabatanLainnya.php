<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class PengaturanJabatanLainnya extends BaseController
{
    protected $siteSettingModel;

    public function __construct()
    {
        $this->siteSettingModel = new SiteSettingModel();
    }

    public function index()
    {
        $settings = [];
        foreach ($this->siteSettingModel->findAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

       return view('adminpage/pengaturan_situs/pengaturan_jabatanlainnya', ['settings' => $settings]);

    }

 public function save()
{
  $fields = [
    'jabatanlainnya_logout_button_text',
    'jabatanlainnya_logout_button_color',
    'jabatanlainnya_logout_button_text_color',
    'jabatanlainnya_logout_button_hover_color',
    'jabatanlainnya_ami_button_text',
    'jabatanlainnya_ami_button_color',
    'jabatanlainnya_ami_button_text_color',
    'jabatanlainnya_ami_button_hover_color',
    'jabatanlainnya_akreditasi_button_text',
    'jabatanlainnya_akreditasi_button_color',
    'jabatanlainnya_akreditasi_button_text_color',
    'jabatanlainnya_akreditasi_button_hover_color',
];

    foreach ($fields as $key) {
        $value = $this->request->getPost($key);
        $this->siteSettingModel->saveSetting($key, $value); // ✅ Panggil method custom kamu
    }

    return redirect()->to('pengaturan-jabatanlainnya')
                     ->with('success', 'Pengaturan tombol logout berhasil disimpan!');
}

}
