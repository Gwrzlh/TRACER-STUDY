<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value'];
    protected $useTimestamps = true;

    /**
     * Ambil semua pengaturan dan kembalikan dalam bentuk array key => value.
     */
    public function getSettings()
    {
        $settings = $this->findAll();
        $data = [];
        foreach ($settings as $s) {
            $data[$s['setting_key']] = $s['setting_value'];
        }
        return $data;
    }

    /**
     * Simpan satu pengaturan berdasarkan key.
     * Jika key sudah ada → update.
     * Jika belum ada → insert baru.
     */
    public function saveSetting($key, $value)
    {
        $exists = $this->where('setting_key', $key)->first();

        if ($exists) {
            $this->where('setting_key', $key)
                 ->set(['setting_value' => $value])
                 ->update();
        } else {
            $this->insert([
                'setting_key'   => $key,
                'setting_value' => $value
            ]);
        }
    }
    public function saveSettings($data)
{
    foreach ($data as $key => $value) {
        $this->saveSetting($key, $value);
    }
}

}
