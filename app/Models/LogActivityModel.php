<?php

namespace App\Models;

use CodeIgniter\Model;

class LogActivityModel extends Model
{
    protected $table            = 'log_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'action_type', 'description', 'ip_adress', 'user_agent', 'created_at'];

    protected bool $allowEmptyInserts = false;

    public function logAction($action_type, $description = null)
    {
        $request = \Config\Services::request();
        $data = [
            'user_id' => session()->get('id') ?? null,
            'action_type' => $action_type,
            'description' => is_array($description) ? json_encode($description) : $description,
            'ip_adress' => $request->getIPAddress() === '::1' ? 'localhost (::1)' : $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        try {
            $this->insert($data);
            log_message('debug', '[LogActivityModel] Log inserted: ' . json_encode($data));
        } catch (\Exception $e) {
            log_message('error', '[LogActivityModel] Failed to log: ' . $e->getMessage());
        }
    }

    public function getLogs($search = null, $date_range = null, $limit = 0, $offset = 0)
    {
        $builder = $this->select('log_activities.*, COALESCE(da.nama_lengkap, dk.nama_lengkap, account.username, "Guest") as nama_lengkap, log_activities.ip_adress')
                       ->join('account', 'account.id = log_activities.user_id', 'left')
                       ->join('detailaccount_alumni da', 'da.id_account = log_activities.user_id', 'left')
                       ->join('detailaccount_kaprodi dk', 'dk.id_account = log_activities.user_id', 'left');
        if ($search) {
            $builder->groupStart()
                    ->like('action_type', $search)
                    ->orLike('log_activities.ip_adress', $search)
                    ->orLike('account.username', $search)
                    ->orLike('da.nama_lengkap', $search)
                    ->orLike('dk.nama_lengkap', $search)
                    ->groupEnd();
        }
        if ($date_range) {
            $dates = explode(' to ', $date_range);
            if (count($dates) === 2 && !empty($dates[0]) && !empty($dates[1])) {
                $builder->where('log_activities.created_at >=', trim($dates[0]) . ' 00:00:00')
                        ->where('log_activities.created_at <=', trim($dates[1]) . ' 23:59:59');
            } elseif (!empty($dates[0])) {
                $builder->where('DATE(log_activities.created_at)', trim($dates[0]));
            }
        }
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
