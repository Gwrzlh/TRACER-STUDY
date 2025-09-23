<?php

namespace App\Models\LandingPage;

use CodeIgniter\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['setting_key', 'setting_value'];
    protected $useTimestamps = true;
}
