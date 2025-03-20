<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuthGroupSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'name'            => 'administrator',
                'description'     => 'role administrator',
            ],
            [
                'name'            => 'product_manager',
                'description'     => 'role product manager',
            ],
            [
                'name'            => 'customer',
                'description'     => 'role customer',
            ],
        ];
        $this->db->table('auth_groups')->insertBatch($data);
    }
}
