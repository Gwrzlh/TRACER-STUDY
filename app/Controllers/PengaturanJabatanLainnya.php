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

        return view('adminpage/pengaturan_situs/pengaturan_jabatanlainya', ['settings' => $settings]);
    }

    public function save()
    {
        $fields = [
            'jabatanlainnya_logout_button_text',
            'jabatanlainnya_logout_button_color',
            'jabatanlainnya_logout_button_text_color',
            'jabatanlainnya_logout_button_hover_color',
        ];

        foreach ($fields as $key) {
            $value = $this->request->getPost($key);
            $this->siteSettingModel->save([
                'setting_key'   => $key,
                'setting_value' => $value,
            ]);
        }

        return redirect()->to('pengaturan-jabatanlainnya')->with('success', 'Pengaturan tombol logout berhasil disimpan!');
    }
}
