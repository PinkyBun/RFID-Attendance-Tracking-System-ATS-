<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'BSIT 4A',
                'course'     => 'Bachelor of Science in Information Technology',
                'year_level' => 4,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'BSIT 3B',
                'course'     => 'Bachelor of Science in Information Technology',
                'year_level' => 3,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'BSIT 2C',
                'course'     => 'Bachelor of Science in Information Technology',
                'year_level' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'BSIT 1D',
                'course'     => 'Bachelor of Science in Information Technology',
                'year_level' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name'       => 'BSCS 3A',
                'course'     => 'Bachelor of Science in Computer Science',
                'year_level' => 3,
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('sections')->insertBatch($data);
    }
}
