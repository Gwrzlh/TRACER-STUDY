<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LogActivityModel;
// use App\Commands\LogArchive;

class LogController extends BaseController
{
    protected $logActivityModel;
    // protected $archiveCommand;

    public function __construct()
    {
        $this->logActivityModel = new LogActivityModel();
        // $this->archiveCommand = new LogArchive();
    }


    public function dashboard()
    {
        $data['stats'] = $this->logActivityModel->getArchiveStats();
        $data['retention_config'] = new \Config\LogRetention();
   
        return view('adminpage/log_activities/dashboard', $data);
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
public function manualArchive()
{
    try {
        // Gunakan layanan commands CI4 untuk menjalankan command
        $commands = \Config\Services::commands();
        
        // Tangkap output dari command
        ob_start();
        $commands->run('logs:archive', []);
        $output = ob_get_clean();
        
        // Tentukan pesan berdasarkan output
        $message = $output ?: 'Tidak ada log yang memenuhi kriteria untuk diarsipkan.';
        if (strpos($output, 'Tidak ada log untuk diarsipkan') !== false) {
            $message = 'Tidak ada log yang cukup lama untuk diarsipkan (kurang dari 30 hari).';
        }
        
        // Log aksi ke model untuk audit
        $this->logActivityModel->logAction('Manual Archive', 'Admin menjalankan arsip manual. Hasil: ' . $message, 'INFO');
        
        // Log ke file sistem
        log_message('info', 'Arsip manual selesai dengan output: ' . $message);
        
        // Redirect dengan pesan sukses
        return redirect()->to('admin/log_activities/dashboard')->with('message', $message);
    } catch (\Throwable $t) {
        // Log error dengan detail
        log_message('critical', 'Gagal menjalankan arsip manual: ' . $t->getMessage() . ' (File: ' . $t->getFile() . ' Line: ' . $t->getLine() . ') Trace: ' . $t->getTraceAsString());
        $this->logActivityModel->logAction('Manual Archive Error', 'Gagal arsip manual: ' . $t->getMessage(), 'ERROR');
        
        // Redirect dengan pesan error
        return redirect()->to('admin/log_activities/dashboard')->with('error', 'Terjadi kesalahan saat menjalankan arsip: ' . esc($t->getMessage()) . '. Silakan cek log aplikasi.');
    }
}

public function manualCleanup()
{
    try {
        $commands = \Config\Services::commands();
        
        ob_start();
        $commands->run('logs:cleanup', ['--force' => true]);
        $output = ob_get_clean();
        
        // Tentukan pesan berdasarkan output
        $message = $output ?: 'Tidak ada log yang memenuhi kriteria untuk dihapus.';
        if (strpos($output, 'Tidak ada log untuk dihapus') !== false) {
            $message = 'Tidak ada log di arsip yang melebihi periode retensi.';
        }
        
        $this->logActivityModel->logAction('Manual Cleanup', 'Admin menjalankan pembersihan manual. Hasil: ' . $message, 'INFO');
        log_message('info', 'Pembersihan manual selesai dengan output: ' . $message);
        
        return redirect()->to('admin/log_activities/dashboard')->with('message', $message);
    } catch (\Throwable $t) {
        log_message('critical', 'Gagal menjalankan pembersihan manual: ' . $t->getMessage() . ' (File: ' . $t->getFile() . ' Line: ' . $t->getLine() . ') Trace: ' . $t->getTraceAsString());
        $this->logActivityModel->logAction('Manual Cleanup Error', 'Gagal pembersihan manual: ' . $t->getMessage(), 'ERROR');
        
        return redirect()->to('admin/log_activities/dashboard')->with('error', 'Terjadi kesalahan saat menjalankan pembersihan: ' . esc($t->getMessage()) . '. Silakan cek log aplikasi.');
    }
}

}
