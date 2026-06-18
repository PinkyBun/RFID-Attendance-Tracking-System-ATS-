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
        $bsit3B = $sections[1]['id'] ?? 2;
        $bsit2C = $sections[2]['id'] ?? 3;
        $bsit1D = $sections[3]['id'] ?? 4;
        $bscs3A = $sections[4]['id'] ?? 5;

        // Clean table safely avoiding FK constraints
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('subjects')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');

        $data = [
            // 1st Year (BSIT 1D)
            [
                'code'       => 'SIA101',
                'name'       => 'System Integration and Architecture',
                'section_id' => $bsit1D,
                'year_level' => 1,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'PROG101',
                'name'       => 'Computer Programming 1',
                'section_id' => $bsit1D,
                'year_level' => 1,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 2nd Year (BSIT 2C)
            [
                'code'       => 'HCI102',
                'name'       => 'Human Computer Interaction',
                'section_id' => $bsit2C,
                'year_level' => 2,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'DB201',
                'name'       => 'Database Management Systems 1',
                'section_id' => $bsit2C,
                'year_level' => 2,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 3rd Year (BSIT 3B)
            [
                'code'       => 'NET201',
                'name'       => 'Networking 2',
                'section_id' => $bsit3B,
                'year_level' => 3,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'SENG101',
                'name'       => 'Software Engineering',
                'section_id' => $bsit3B,
                'year_level' => 3,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 3rd Year (BSCS 3A)
            [
                'code'       => 'SIA101', // Repeat to match template testing flow
                'name'       => 'System Integration and Architecture (CS)',
                'section_id' => $bscs3A,
                'year_level' => 3,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // 4th Year (BSIT 4A)
            [
                'code'       => 'HCI102', // Advanced HCI matching template flow
                'name'       => 'Advanced Human Computer Interaction',
                'section_id' => $bsit4A,
                'year_level' => 4,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code'       => 'CAP201',
                'name'       => 'Capstone Project 2',
                'section_id' => $bsit4A,
                'year_level' => 4,
                'is_active'  => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('subjects')->insertBatch($data);

        // Add Schedules for some of these subjects so the views don't break
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('subject_schedules')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        
        $schedules = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        
        foreach($subjects as $i => $subj) {
            $schedules[] = [
                'subject_id'  => $subj['id'],
                'day_of_week' => $days[$i % 5],
                'start_time'  => '08:00:00',
                'end_time'    => '11:00:00'
            ];
            // Second session per week for a few
            if ($i % 2 == 0) {
                $schedules[] = [
                    'subject_id'  => $subj['id'],
                    'day_of_week' => $days[($i + 2) % 5],
                    'start_time'  => '13:00:00',
                    'end_time'    => '16:00:00'
                ];
            }
        }

        $this->db->table('subject_schedules')->insertBatch($schedules);
    }
}
