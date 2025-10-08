<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class PengaturanKaprodi extends BaseController
{
    protected $siteSettingModel;

    public function __construct()
    {
        $this->siteSettingModel = new SiteSettingModel();
    }

    public function index()
    {
        // Ambil semua setting
        $settings = $this->siteSettingModel->getSettings();

        $data = [
            'settings' => $settings
        ];

        return view('adminpage/pengaturan_situs/pengaturan_kaprodi', $data);
    }

    public function save()
    {
        $fields = [
            'kaprodi_logout_button_text',
            'kaprodi_logout_button_color',
            'kaprodi_logout_button_text_color',
            'kaprodi_logout_button_hover_color',
        ];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            $this->siteSettingModel->saveSetting($field, $value);
        }

        return redirect()->to(base_url('pengaturan-kaprodi'))
                         ->with('success', 'Pengaturan tombol logout Kaprodi berhasil disimpan!');
    }
}
