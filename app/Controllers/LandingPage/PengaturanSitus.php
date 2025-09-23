<?php

namespace App\Controllers\LandingPage;

use App\Controllers\BaseController;
use App\Models\LandingPage\SiteSettingModel;

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
        // Pengguna
        'pengguna_button_text'        => get_setting('pengguna_button_text', 'Tambah Pengguna'),
        'pengguna_button_color'       => get_setting('pengguna_button_color', '#007bff'),
        'pengguna_button_text_color'  => get_setting('pengguna_button_text_color', '#ffffff'),
        'pengguna_button_hover_color' => get_setting('pengguna_button_hover_color', '#0056b3'),
        'pengguna_perpage_default'    => get_setting('pengguna_perpage_default', '10'),

        // Login Button
        'login_button_text'           => get_setting('login_button_text', 'Login'),
        'login_button_color'          => get_setting('login_button_color', '#007bff'),
        'login_button_text_color'     => get_setting('login_button_text_color', '#ffffff'),
        'login_button_hover_color'    => get_setting('login_button_hover_color', '#0056b3'),
        
     // Satuan Organisasi
        'org_button_text'             => get_setting('org_button_text', 'Tambah Satuan Organisasi'),
        'org_button_color'            => get_setting('org_button_color', '#28a745'),
        'org_button_text_color'       => get_setting('org_button_text_color', '#ffffff'),
        'org_button_hover_color'      => get_setting('org_button_hover_color', '#218838'),
    // tombol logout 
        'logout_button_text'        => get_setting('logout_button_text', 'Logout'),
        'logout_button_color'       => get_setting('logout_button_color', '#dc3545'),
        'logout_button_text_color'  => get_setting('logout_button_text_color', '#ffffff'),
        'logout_button_hover_color' => get_setting('logout_button_hover_color', '#a71d2a'),
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

        return redirect()->to(base_url('admin/pengaturan-situs'))->with('success', 'Pengaturan berhasil disimpan.');

    }
}
