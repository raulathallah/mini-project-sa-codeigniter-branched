<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'category_id' => [
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
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
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
        $this->forge->addKey('category_id', true);
        $this->forge->createTable('categories');
    }

    public function down()
    {
        //
        $this->forge->dropTable('categories');
    }
}
