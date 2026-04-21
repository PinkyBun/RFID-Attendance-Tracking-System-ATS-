<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run()
    {
        // Get metadata
        $sections = $this->db->table('sections')->get()->getResultArray();
        $bsit4A = $sections[0]['id'] ?? 1;
        
        $subjects = $this->db->table('subjects')->get()->getResultArray();
        $siaId = $subjects[0]['id'] ?? 1;
        $hciId = $subjects[1]['id'] ?? 2;

        // 1. Regular Students for BSIT 4A
        $regularStudents = [
            [
                'rfid_uid'       => 'A1B2C3D4',
                'student_number' => '2024-0001',
                'first_name'     => 'John',
                'last_name'      => 'Doe',
                'type'           => 'regular',
                'section_id'     => $bsit4A,
                'year_level'     => 4,
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            [
                'rfid_uid'       => 'E5F6G7H8',
                'student_number' => '2024-0002',
                'first_name'     => 'Jane',
                'last_name'      => 'Smith',
                'type'           => 'regular',
                'section_id'     => $bsit4A,
                'year_level'     => 4,
                'is_active'      => 1,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('students')->insertBatch($regularStudents);
        
        // Auto-enroll regular students in BSIT 4A subjects
        $studentIds = $this->db->table('students')->where('type', 'regular')->get()->getResultArray();
        $sectionSubjects = $this->db->table('subjects')->where('section_id', $bsit4A)->get()->getResultArray();
        
        $enrollments = [];
        foreach ($studentIds as $s) {
            foreach ($sectionSubjects as $sub) {
                $enrollments[] = [
                    'student_id'  => $s['id'],
                    'subject_id'  => $sub['id'],
                    'enrolled_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        if (!empty($enrollments)) {
            $this->db->table('student_enrollments')->insertBatch($enrollments);
        }

        // 2. Irregular Student
        $irregularStudent = [
            'rfid_uid'       => 'RFID-IRREG-99',
            'student_number' => '2024-IRR-001',
            'first_name'     => 'Alice',
            'last_name'      => 'Wonder',
            'type'           => 'irregular',
            'section_id'     => null,
            'year_level'     => 4,
            'is_active'      => 1,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];

        $this->db->table('students')->insert($irregularStudent);
        $irrId = $this->db->insertID();

        // Enroll irregular student in SIA only
        $this->db->table('student_enrollments')->insert([
            'student_id'  => $irrId,
            'subject_id'  => $siaId,
            'enrolled_at' => date('Y-m-d H:i:s')
        ]);
    }
}
