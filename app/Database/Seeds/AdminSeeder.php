<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'       => 'Admin Teacher',
            'email'      => 'admin@example.com',
            'password'   => password_hash('password123', PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Simple check to prevent duplicate admin
        $db = \Config\Database::connect();
        $exists = $db->table('users')->where('email', $data['email'])->get()->getRow();
        
        if (!$exists) {
            $db->table('users')->insert($data);
            echo "Admin user created: admin@example.com / password123\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}
