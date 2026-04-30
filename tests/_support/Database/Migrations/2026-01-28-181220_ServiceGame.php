<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ServiceGame extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_service' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_game' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'details' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
        $this->forge->addKey(['id_service','id_game','id_member'], true);
        $this->forge->addForeignKey('id_service','service','id','CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_game','game','id','CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_member','member','id','CASCADE', 'RESTRICT');
        $this->forge->createTable('service_game',true);
    }

    public function down()
    {
        $this->forge->dropTable('service_game');
    }
}
