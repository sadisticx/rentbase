<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RentbaseSeeder extends Seeder
{
    public function run()
    {
        // Insert sample users
        // Password for all users is 'password'
        $users = [
            [
                'username'   => 'owner1',
                'password'   => password_hash('password', PASSWORD_BCRYPT),
                'role'       => 'owner',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'tenant1',
                'password'   => password_hash('password', PASSWORD_BCRYPT),
                'role'       => 'tenant',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'employee1',
                'password'   => password_hash('password', PASSWORD_BCRYPT),
                'role'       => 'employee',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
