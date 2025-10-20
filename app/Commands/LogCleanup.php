<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\LogRetention;

class LogCleanup extends BaseCommand
{
    protected $group = 'Maintenance';
    protected $name = 'logs:cleanup';
    protected $description = 'Delete old logs based on retention policy';
   
    protected $options = [
        '--dry-run' => 'Show what would be deleted without actually deleting',
        '--severity' => 'Target specific severity (CRITICAL, ERROR, WARNING, INFO, DEBUG)',
        '--force' => 'Skip confirmation prompt',
    ];

    public function run(array $params)
    {
        $config = new LogRetention();
        $db = \Config\Database::connect();
       
        $dryRun = CLI::getOption('dry-run');
        $targetSeverity = CLI::getOption('severity');
        $force = CLI::getOption('force');
       
        CLI::write('Log Cleanup Process', 'yellow');
        CLI::write(str_repeat('=', 50));
       
        $severities = $targetSeverity ? [$targetSeverity] : array_keys($config->retentionPeriods);
        $totalDeleted = 0;
       
        foreach ($severities as $severity) {
            if (!isset($config->retentionPeriods[$severity])) {
                CLI::error("Unknown severity: {$severity}");
                continue;
            }
           
            $days = $config->retentionPeriods[$severity];
            $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
           
            // Count from archive table
            $count = $db->table('log_activities_archive')
                       ->where('severity', $severity)
                       ->where('created_at <', $cutoffDate)
                       ->countAllResults(false);
           
            if ($count === 0) {
                CLI::write("[{$severity}] No logs to delete.", 'green');
                continue;
            }
           
            CLI::write("[{$severity}] Found {$count} logs older than {$days} days.", 'cyan');
           
            if ($dryRun) {
                CLI::write(" → Would delete {$count} records (DRY RUN)", 'yellow');
                continue;
            }
           
            // Confirmation for large deletes
            if ($count > 5000 && !$force) {
                $confirm = CLI::prompt("Delete {$count} {$severity} logs?", ['y', 'n']);
                if ($confirm !== 'y') {
                    CLI::write(" → Skipped by user.", 'yellow');
                    continue;
                }
            }
           
            // Delete in batches
            // Inline comment: Deletion is direct here for simplicity, but for very large datasets, consider batching with limit/offset in a loop
            $deleted = $db->table('log_activities_archive')
                         ->where('severity', $severity)
                         ->where('created_at <', $cutoffDate)
                         ->delete();
           
            CLI::write(" ✓ Deleted {$deleted} {$severity} logs.", 'green');
            $totalDeleted += $deleted;
        }
       
        CLI::write(str_repeat('=', 50));
        CLI::write("Total deleted: {$totalDeleted} logs", 'green');
       
        // Send email if large deletion and config enabled
        if ($totalDeleted > 5000 && $config->notifyOnLargeDelete) {
            $this->sendNotification($totalDeleted);
        }
    }
   
    private function sendNotification($count)
    {
        $email = \Config\Services::email();
        $config = new LogRetention();
       
        $email->setTo($config->notifyEmail);
        $email->setSubject('Large Log Cleanup Performed');
        $email->setMessage("A total of {$count} log records were deleted from the archive on " . date('Y-m-d H:i:s'));
       
        if ($email->send()) {
            CLI::write('Notification email sent.', 'green');
        } else {
            CLI::error('Failed to send notification email.');
        }
    }
}