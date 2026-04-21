<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubjectSchedulesTable extends Migration
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
            'day_of_week' => [
                'type'       => 'ENUM',
                'constraint' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'null'       => false,
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => false,
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('subject_id');
        $this->forge->addForeignKey('subject_id', 'subjects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subject_schedules');
    }

    public function down(): void
    {
        $this->forge->dropTable('subject_schedules', true);
    }
}
