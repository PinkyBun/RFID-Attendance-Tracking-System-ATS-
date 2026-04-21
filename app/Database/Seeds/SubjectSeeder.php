<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // Get section IDs
        $sections = $this->db->table('sections')->get()->getResultArray();
        $bsit4A = $sections[0]['id'] ?? 1;
        $bsit4B = $sections[1]['id'] ?? 2;

        $data = [
            [
                'code'       => 'SIA101',
                'name'       => 'System Integration and Architecture',
                'section_id' => $bsit4A,
                'year_level' => 4,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'HCI102',
                'name'       => 'Human Computer Interaction',
                'section_id' => $bsit4A,
                'year_level' => 4,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'NET201',
                'name'       => 'Networking 2',
                'section_id' => $bsit4B,
                'year_level' => 4,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('subjects')->insertBatch($data);

        // Add Schedules for these subjects
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $schedules = [
            [
                'subject_id'  => $subjects[0]['id'], // SIA101
                'day_of_week' => 'Mon',
                'start_time'  => '08:00:00',
                'end_time'    => '11:00:00'
            ],
            [
                'subject_id'  => $subjects[0]['id'], // SIA101
                'day_of_week' => 'Wed',
                'start_time'  => '08:00:00',
                'end_time'    => '10:00:00'
            ],
            [
                'subject_id'  => $subjects[1]['id'], // HCI102
                'day_of_week' => 'Tue',
                'start_time'  => '13:00:00',
                'end_time'    => '16:00:00'
            ],
            [
                'subject_id'  => $subjects[2]['id'], // NET201
                'day_of_week' => 'Thu',
                'start_time'  => '09:00:00',
                'end_time'    => '12:00:00'
            ],
        ];

        $this->db->table('subject_schedules')->insertBatch($schedules);
    }
}
