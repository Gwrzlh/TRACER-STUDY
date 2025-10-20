<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSeverityToLogs extends Migration
{
    public function up()
    {
        // Add severity column
        $this->forge->addColumn('log_activities', [
            'severity' => [
                'type' => 'ENUM',
                'constraint' => ['CRITICAL', 'ERROR', 'WARNING', 'INFO', 'DEBUG'],
                'default' => 'INFO',
                'after' => 'action_type',
            ],
        ]);
       
        // Add indexes
        $this->db->query('ALTER TABLE log_activities ADD INDEX idx_created_severity (created_at, severity)');
        $this->db->query('ALTER TABLE log_activities ADD INDEX idx_user_created (user_id, created_at)');
    }
   
    public function down()
    {
        $this->db->query('ALTER TABLE log_activities DROP INDEX idx_created_severity');
        $this->db->query('ALTER TABLE log_activities DROP INDEX idx_user_created');
        $this->forge->dropColumn('log_activities', 'severity');
    }
}