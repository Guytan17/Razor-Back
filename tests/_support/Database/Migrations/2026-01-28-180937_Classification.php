<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Classification extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 2,
                'null' => false,
            ],
            'explanation' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('classification');
    }

    public function down()
    {
        $this->forge->dropTable('classification');
    }
}
