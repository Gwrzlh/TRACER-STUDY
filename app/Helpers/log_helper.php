<?php


use App\Models\ErrorLogModel;

if (!function_exists('log_error')) {
    function log_error($action, $message, $fileName = null)
    {
        $model = new \App\Models\ErrorLogModel();

        // 🔥 Hapus semua log lama sebelum menyimpan yang baru
        $model->truncate();

        $data = [
            'user_id'    => session()->get('id'),
            'action'     => $action,
            'message'    => $message,
            'file_name'  => $fileName,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ];

        try {
            $model->insert($data);
            log_message('error', '[log_error] inserted new log successfully');
        } catch (\Exception $e) {
            log_message('error', '[log_error] failed to insert log: ' . $e->getMessage());
        }
    }
}
