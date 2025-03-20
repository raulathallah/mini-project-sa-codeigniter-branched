<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'product_id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'description' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'price' => [
                'type'          => 'INT',
            ],
            'stock' => [
                'type'          => 'INT',
            ],
            'category_id' => [
                'type'          => 'INT',
            ],
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'is_new' => [
                'type'          => 'BOOLEAN',
            ],
            'is_sale' => [
                'type'          => 'BOOLEAN'
            ],
            'created_at' => [
                'type'          => 'timestamp'
            ],
            'updated_at' => [
                'type'          => 'timestamp'
            ],
            // 'deleted_at' => [
            //     'type'          => 'timestamp'
            // ],
        ]);
        $this->forge->addKey('product_id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'category_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        //
        $this->forge->dropTable('products');
    }
}
