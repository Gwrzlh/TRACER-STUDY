<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LogActivityModel;
use Config\LogRetention;

class LogArchive extends BaseCommand
{
    protected $group = 'Maintenance';
    protected $name = 'logs:archive';
    protected $description = 'Archive old log activities to separate table';

    public function run(array $params = [])
    {
        $config = new LogRetention();
        $model = new LogActivityModel();
        $db = \Config\Database::connect();

        $archiveAfterDays = $config->archiveAfterDays ?? 30;
        $batchSize = $config->batchSize ?? 1000;
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$archiveAfterDays} days"));

        CLI::write("Memulai arsip log lebih lama dari {$archiveAfterDays} hari (sebelum {$cutoffDate})", 'yellow');
        log_message('debug', "Memulai arsip log sebelum: {$cutoffDate}");

        // Hitung total log yang akan diarsipkan
        $count = $model->where('created_at <', $cutoffDate)->countAllResults();
        CLI::write("Total log yang akan diarsipkan: {$count}", 'blue');
        log_message('debug', "Total log untuk arsip: {$count}");

        if ($count === 0) {
            CLI::write('Tidak ada log untuk diarsipkan.', 'green');
            log_message('info', 'Tidak ada log untuk diarsipkan.');
            return;
        }

        $archived = 0;
        CLI::showProgress(0, $count);

        while (true) {
            $db->transStart();

            // Ambil log dalam batch
            $logs = $model->where('created_at <', $cutoffDate)
                          ->limit($batchSize)
                          ->findAll();
            
            if (empty($logs)) {
                CLI::write('Tidak ada lagi log untuk diarsipkan.', 'green');
                log_message('debug', 'Tidak ada lagi log untuk diarsipkan.');
                break;
            }

            try {
                // Insert ke tabel arsip
                foreach ($logs as $log) {
                    // Validasi data
                    if (empty($log['action_type']) || empty($log['created_at'])) {
                        log_message('error', 'Data log tidak lengkap: ' . json_encode($log));
                        continue;
                    }

                    $dataToInsert = [
                        'user_id' => $log['user_id'],
                        'action_type' => substr($log['action_type'], 0, 100), // Batasi panjang
                        'severity' => $log['severity'] ?? 'INFO',
                        'description' => $log['description'],
                        'ip_adress' => substr($log['ip_adress'], 0, 45), // Batasi panjang
                        'user_agent' => $log['user_agent'] ? substr($log['user_agent'], 0, 255) : null,
                        'created_at' => $log['created_at'],
                        'archived_at' => date('Y-m-d H:i:s'), // Aman untuk DATETIME atau TIMESTAMP
                    ];

                    log_message('debug', 'Insert data ke arsip: ' . json_encode($dataToInsert));
                    $db->table('log_activities_archive')->insert($dataToInsert);
                }

                // Hapus dari tabel utama
                $ids = array_column($logs, 'id');
                log_message('debug', 'Menghapus ID dari log_activities: ' . json_encode($ids));
                $model->whereIn('id', $ids)->delete();

                $db->transComplete();

                if ($db->transStatus() === false) {
                    CLI::error('Transaksi gagal untuk batch.');
                    log_message('error', 'Transaksi gagal untuk batch.');
                    throw new \Exception('Transaksi gagal');
                }

                $archived += count($logs);
                CLI::showProgress($archived, $count);
            } catch (\Exception $e) {
                $db->transRollback();
                CLI::error('Error: ' . $e->getMessage());
                log_message('error', 'Gagal arsip: ' . $e->getMessage());
                throw $e;
            }
        }

        CLI::write("Berhasil mengarsipkan {$archived} log!", 'green');
        log_message('info', "Berhasil mengarsipkan {$archived} log.");
        $this->logAction('System Archive', "Berhasil mengarsipkan {$archived} log", 'INFO');
    }

    private function logAction($action_type, $description, $severity)
    {
        $model = new \App\Models\LogActivityModel();
        $model->logAction($action_type, $description, $severity);
    }
}