<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductImageTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'product_image_id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'          => 'INT',
            ],
            'image_path' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255',
                'null'          => true,
                'default'       => null,
            ],
            'is_primary' => [
                'type'          => 'BOOLEAN',
            ],
            'created_at' => [
                'type'          => 'timestamp'
            ],
            'updated_at' => [
                'type'          => 'timestamp'
            ],
        ]);
        $this->forge->addKey('product_image_id', true);
        $this->forge->addForeignKey('product_id', 'products', 'product_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_images');
    }

    public function down()
    {
        //
        $this->forge->dropTable('product_images');
    }
}
