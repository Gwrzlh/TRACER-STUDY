<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class PengaturanAtasan extends BaseController
{
    protected $siteSettingModel;

    public function __construct()
    {
        $this->siteSettingModel = new SiteSettingModel();
    }

    public function index()
    {
        // Ambil semua pengaturan dari database
        $settings = $this->siteSettingModel->getSettings();

        $data = [
            'settings' => $settings
        ];

        return view('adminpage/pengaturan_situs/pengaturan_atasan', $data);
    }

    public function save()
    {
        $data = $this->request->getPost();

        if ($data) {
            foreach ($data as $key => $value) {
                $existing = $this->siteSettingModel->where('setting_key', $key)->first();

                if ($existing) {
                    $this->siteSettingModel->update($existing['id'], ['setting_value' => $value]);
                } else {
                    $this->siteSettingModel->insert([
                        'setting_key' => $key,
                        'setting_value' => $value
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Pengaturan Atasan berhasil disimpan.');
        }

        return redirect()->back()->with('error', 'Tidak ada data yang disimpan.');
    }
}
