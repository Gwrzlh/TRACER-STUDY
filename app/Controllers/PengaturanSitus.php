<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class PengaturanSitus extends BaseController
{
    protected $siteSettingModel;

    public function __construct()
    {
        $this->siteSettingModel = new SiteSettingModel();
    }

    public function index()
    {
        $data['settings'] = [
            'pengguna_button_text'        => get_setting('pengguna_button_text', 'Tambah Pengguna'),
            'pengguna_button_color'       => get_setting('pengguna_button_color', 'bg-blue-600'),
            'pengguna_button_text_color'  => get_setting('pengguna_button_text_color', 'text-white'),
            'pengguna_button_hover_color' => get_setting('pengguna_button_hover_color', 'hover:bg-blue-700'),
            'pengguna_perpage_default' => get_setting('pengguna_perpage_default', '10'),

        ];

        return view('adminpage/pengaturan_situs/index', $data);
    }

    public function save()
    {
        $settings = $this->request->getPost();

        foreach ($settings as $key => $value) {
            $existing = $this->siteSettingModel->where('setting_key', $key)->first();

            if ($existing) {
                $this->siteSettingModel->update($existing['id'], [
                    'setting_value' => $value
                ]);
            } else {
                $this->siteSettingModel->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value
                ]);
            }
        }

        return redirect()->to(base_url('pengaturan-situs'))->with('success', 'Pengaturan berhasil disimpan.');
    }
}
