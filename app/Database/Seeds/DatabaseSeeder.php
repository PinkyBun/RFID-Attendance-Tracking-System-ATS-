<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Order is important due to Foreign Keys
        $this->call('AdminSeeder');
        $this->call('SectionSeeder');
        $this->call('SubjectSeeder');
        $this->call('StudentSeeder');
        
        echo "Database seeding completed successfully!\n";
    }
}
