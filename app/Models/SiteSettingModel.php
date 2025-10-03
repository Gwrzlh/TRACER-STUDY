<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value'];
    protected $useTimestamps = true;

    // Ambil semua setting jadi array key => value
    public function getSettings()
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $s) {
            $result[$s['setting_key']] = $s['setting_value'];
        }
        return $result;
    }

    // Simpan atau update setting
    public function saveSettings($data)
    {
        foreach ($data as $key => $value) {
            $row = $this->where('setting_key', $key)->first();
            if ($row) {
                $this->update($row['id'], ['setting_value' => $value]);
            } else {
                $this->insert(['setting_key' => $key, 'setting_value' => $value]);
            }
        }
    }
}
