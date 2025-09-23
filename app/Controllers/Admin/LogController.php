<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Support\LogActivityModel;

class LogController extends BaseController
{
    protected $logActivityModel;

    public function __construct()
    {
        $this->logActivityModel = new LogActivityModel();
    }

   public function index()
    {
        $search = $this->request->getGet('search');
        $date_range = $this->request->getGet('date_range');
        $data['logs'] = $this->logActivityModel->getLogs($search, $date_range);
        $data['search'] = $search;
        $data['date_range'] = $date_range;

        return view('adminpage/log_activities/index', $data);
    }

    public function export()
    {
        $search = $this->request->getGet('search');
        $date_range = $this->request->getGet('date_range');
        $logs = $this->logActivityModel->getLogs($search, $date_range, 0, 0);

        $csv = fopen('php://output', 'w');
        fputs($csv, "\xEF\xBB\xBF"); // UTF-8 BOM
        fputcsv($csv, ['Nama Account', 'Jenis Aktivitas', 'IP Address', 'Tanggal Waktu', 'Detail']);

        foreach ($logs as $log) {
            fputcsv($csv, [
                $log['nama_lengkap'] ?: 'Guest (ID: ' . ($log['user_id'] ?? 'N/A') . ')',
                $log['action_type'],
                $log['ip_adress'],
                date('d M Y H:i:s', strtotime($log['created_at'])),
                $log['description'] ?? ''
            ]);
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="log_activities_' . date('Ymd_His') . '.csv"');
        exit;
    }
}
