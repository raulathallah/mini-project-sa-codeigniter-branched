<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ProductImageSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'product_id'            => 1,
                'image_path'            => 'images/f1.jpg',
                'is_primary'            => true,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'product_id'            => 2,
                'image_path'            => 'images/f2.jpg',
                'is_primary'            => true,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'product_id'            => 3,
                'image_path'            => 'images/f3.jpg',
                'is_primary'            => true,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
            [
                'product_id'            => 4,
                'image_path'            => 'images/f4.jpg',
                'is_primary'            => true,
                'created_at'            => new Time(),
                'updated_at'            => new Time(),
            ],
        ];
        $this->db->table('product_images')->insertBatch($data);
    }
}
