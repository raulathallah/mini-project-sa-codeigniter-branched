<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CategorySeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'name'                  => 'Food',
                'description'           => 'A category for every food in this restaurant',
                'status'                => 'active',
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'name'                  => 'Beverage',
                'description'           => 'A category for every drinks and desert in this restaurant',
                'status'                => 'active',
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
        ];
        $this->db->table('categories')->insertBatch($data);
    }
}
