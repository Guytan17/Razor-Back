<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Gym extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'fbi_code' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'unique' => true,
            ],
            'id_address' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('id_address','address','id','CASCADE','RESTRICT');
        $this->forge->createTable('gym',true);
    }

    public function down()
    {
        $this->forge->dropTable('gym',true);
    }
}
