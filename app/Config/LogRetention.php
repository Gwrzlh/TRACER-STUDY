<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class LogRetention extends BaseConfig
{
    // Retention periods (in days)
    public $retentionPeriods = [
        'CRITICAL' => 365, // 1 year
        'ERROR' => 180, // 6 months
        'WARNING' => 90, // 3 months
        'INFO' => 30, // 1 month
        'DEBUG' => 7, // 1 week
    ];

    // Archive settings
    public $archiveAfterDays = 30; // Move to archive after 30 days
    public $deleteAfterArchiveDays = 90; // Delete from archive after 90 days
   
    // Performance settings
    public $batchSize = 1000; // Process 1000 records at a time
    public $enableCompression = true; // Compress old descriptions
   
    // Notification settings
    public $notifyOnLargeDelete = true; // Send email if deleting >5000 records
    public $notifyEmail = 'admin@tracerstudy.com';
}