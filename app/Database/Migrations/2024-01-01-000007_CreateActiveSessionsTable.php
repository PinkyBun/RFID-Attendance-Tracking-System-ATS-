<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActiveSessionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'schedule_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'session_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'opened_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'closed_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('is_active');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'subject_schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('active_sessions');
    }

    public function down(): void
    {
        $this->forge->dropTable('active_sessions', true);
    }
}
