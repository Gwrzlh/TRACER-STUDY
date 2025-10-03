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

            // Logout
            'logout_button_text'          => get_setting('logout_button_text', 'Logout'),
            'logout_button_color'         => get_setting('logout_button_color', '#dc3545'),
            'logout_button_text_color'    => get_setting('logout_button_text_color', '#ffffff'),
            'logout_button_hover_color'   => get_setting('logout_button_hover_color', '#a71d2a'),

            // Import Akun
            'import_button_text'          => get_setting('import_button_text', 'Import Akun'),
            'import_button_color'         => get_setting('import_button_color', '#22c55e'),
            'import_button_text_color'    => get_setting('import_button_text_color', '#ffffff'),
            'import_button_hover_color'   => get_setting('import_button_hover_color', '#16a34a'),

            // Survey
            'survey_button_text'          => get_setting('survey_button_text', 'Mulai Survey'),
            'survey_button_color'         => get_setting('survey_button_color', '#0d6efd'),
            'survey_button_text_color'    => get_setting('survey_button_text_color', '#ffffff'),
            'survey_button_hover_color'   => get_setting('survey_button_hover_color', '#0b5ed7'),

            // ✅ Filter Button
            'filter_button_text'          => get_setting('filter_button_text', 'Filter'),
            'filter_button_color'         => get_setting('filter_button_color', '#17a2b8'),
            'filter_button_text_color'    => get_setting('filter_button_text_color', '#ffffff'),
            'filter_button_hover_color'   => get_setting('filter_button_hover_color', '#138496'),

            // ✅ Reset Button
            'reset_button_text'           => get_setting('reset_button_text', 'Reset'),
            'reset_button_color'          => get_setting('reset_button_color', '#6c757d'),
            'reset_button_text_color'     => get_setting('reset_button_text_color', '#ffffff'),
            'reset_button_hover_color'    => get_setting('reset_button_hover_color', '#5a6268'),
            
            //export button 
            'export_button_text'       => $this->request->getPost('export_button_text'),
            'export_button_color'      => $this->request->getPost('export_button_color'),
            'export_button_text_color' => $this->request->getPost('export_button_text_color'),
            'export_button_hover_color'=> $this->request->getPost('export_button_hover_color'),
        ];

        return view('adminpage/pengaturan_situs/index', $data);
    }   

    public function save()
    {
        $settings = $this->request->getPost();

        if (!$settings || !is_array($settings)) {
            return redirect()->back()->with('error', 'Tidak ada data untuk disimpan.');
        }

        foreach ($settings as $key => $value) {
            $cleanValue = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');

            $existing = $this->siteSettingModel->where('setting_key', $key)->first();

            if ($existing) {
                $this->siteSettingModel->update($existing['id'], [
                    'setting_key'   => $key,
                    'setting_value' => $cleanValue
                ]);
            } else {
                $this->siteSettingModel->insert([
                    'setting_key'   => $key,
                    'setting_value' => $cleanValue
                ]);
            }
        }

        return redirect()->to(base_url('pengaturan-situs'))->with('success', 'Pengaturan berhasil disimpan.');
    }
}
