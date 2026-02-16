<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Service extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('service');
    }

    public function down()
    {
       $this->forge->dropTable('service',true);
    }
}
