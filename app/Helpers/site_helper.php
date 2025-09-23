<?php
use App\Models\LandingPage\SiteSettingModel;


if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {
        $model = new SiteSettingModel();
        $setting = $model->where('setting_key', $key)->first();
        return $setting['setting_value'] ?? $default;
    }
}
