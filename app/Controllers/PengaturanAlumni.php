<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiteSettingModel;

class PengaturanAlumni extends BaseController
{
    protected $siteSettingModel;

    public function __construct()
    {
        $this->siteSettingModel = new SiteSettingModel();
    }

    public function index()
    {
        $data['settings'] = $this->siteSettingModel->getSettings();
        return view('adminpage/pengaturan_situs/pengaturan_alumni', $data);
    }

    public function save()
    {
        $post = $this->request->getPost();

        $data = [
        
    // Tombol Dashboard - Lihat Profil
    'dashboard_profil_button_text'        => $post['dashboard_profil_button_text'],
    'dashboard_profil_button_color'       => $post['dashboard_profil_button_color'],
    'dashboard_profil_button_text_color'  => $post['dashboard_profil_button_text_color'],
    'dashboard_profil_button_hover_color' => $post['dashboard_profil_button_hover_color'],

    // Tombol Dashboard - Isi Kuesioner
    'dashboard_kuesioner_button_text'        => $post['dashboard_kuesioner_button_text'],
    'dashboard_kuesioner_button_color'       => $post['dashboard_kuesioner_button_color'],
    'dashboard_kuesioner_button_text_color'  => $post['dashboard_kuesioner_button_text_color'],
    'dashboard_kuesioner_button_hover_color' => $post['dashboard_kuesioner_button_hover_color'],

    
        // 🔹 Tombol Dashboard - Logout
        'dashboard_logout_button_text'        => $post['dashboard_logout_button_text'],
        'dashboard_logout_button_color'       => $post['dashboard_logout_button_color'],
        'dashboard_logout_button_text_color'  => $post['dashboard_logout_button_text_color'],
        'dashboard_logout_button_hover_color' => $post['dashboard_logout_button_hover_color'],
        ];

        $this->siteSettingModel->saveSettings($data);

        return redirect()->to(base_url('pengaturan-alumni'))->with('success', 'Pengaturan Alumni berhasil disimpan!');
    }
}
