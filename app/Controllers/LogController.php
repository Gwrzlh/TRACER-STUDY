<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LogActivityModel;

class LogController extends BaseController
{
    protected $logActivityModel;

    public function __construct()
    {
        $this->logActivityModel = new LogActivityModel();
    }

   public function index()
{
    $search     = $this->request->getGet('search');
    $date_range = $this->request->getGet('date_range');

    $perPage = get_setting('log_perpage_default', 10);

    // ✅ pakai builder + paginate
    $data['logs'] = $this->logActivityModel
        ->getLogsQuery($search, $date_range)
        ->paginate($perPage);

    $data['pager'] = $this->logActivityModel->pager;

    $data['search']     = $search;
    $data['date_range'] = $date_range;

    // setting tombol
    $data['settings'] = [
        'filter_button_text'        => get_setting('filter_button_text', 'Filter'),
        'filter_button_color'       => get_setting('filter_button_color', '#17a2b8'),
        'filter_button_text_color'  => get_setting('filter_button_text_color', '#ffffff'),
        'filter_button_hover_color' => get_setting('filter_button_hover_color', '#138496'),

        'reset_button_text'         => get_setting('reset_button_text', 'Reset'),
        'reset_button_color'        => get_setting('reset_button_color', '#6c757d'),
        'reset_button_text_color'   => get_setting('reset_button_text_color', '#ffffff'),
        'reset_button_hover_color'  => get_setting('reset_button_hover_color', '#5a6268'),

         // ✅ Export CSV
        'export_button_text'        => get_setting('export_button_text', 'Export CSV'),
        'export_button_color'       => get_setting('export_button_color', '#198754'),
        'export_button_text_color'  => get_setting('export_button_text_color', '#ffffff'),
        'export_button_hover_color' => get_setting('export_button_hover_color', '#157347'),
    ];

    return view('adminpage/log_activities/index', $data);
}

  public function export()
{
    $search     = $this->request->getGet('search');
    $date_range = $this->request->getGet('date_range');

    // ✅ Ambil semua data langsung (tanpa paginate)
    $logs = $this->logActivityModel
        ->getLogsQuery($search, $date_range)
        ->get()
        ->getResultArray();

    // Buat CSV
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
