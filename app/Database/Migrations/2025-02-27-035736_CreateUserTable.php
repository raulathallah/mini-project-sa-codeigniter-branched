<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'account_id' => [
                'type'          => 'INT',
                'constraint'    => 5,
                'unsigned'      => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'          => 'INT',
            ],
            'username' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'email' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            // 'password' => [
            //     'type'          => 'VARCHAR',
            //     'constraint'    => '255',
            //     'default'       => null,
            // ],
            'full_name' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'role' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'status' => [
                'type'          => 'VARCHAR',
                'constraint'    => '255'
            ],
            'last_login' => [
                'type'          => 'timestamp',
                'default'       => null,
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
        $this->forge->addKey('account_id', true);
        $this->forge->createTable('accounts');
    }

    public function down()
    {
        //
        $this->forge->dropTable('accounts');
    }
}
