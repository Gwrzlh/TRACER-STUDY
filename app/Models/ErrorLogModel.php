<?php

namespace App\Models;

use CodeIgniter\Model;

class ErrorLogModel extends Model
{
    protected $table = 'error_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'action',
        'message',
        'file_name',
        'ip_address',
        'user_agent',
        'created_at'
    ];
    protected $useTimestamps = false; // karena kita sudah pakai CURRENT_TIMESTAMP di DB
}
