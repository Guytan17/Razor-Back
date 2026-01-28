<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Player extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_team' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'main_team' => [
                'type' => 'BOOLEAN',
                'null' => false,
            ],
            'overqualified' => [
                'type' => 'INT',
                'contraint'=> 1,
                'null' => false,
            ],
            'available' => [
                'type' => 'BOOLEAN',
                'null' => false,
            ],
            'details' => [
                'type' => 'TEXT',
                'null' => true,
            ]
        ]);
        $this->forge->addForeignKey('id_member', 'member', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_team', 'team', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('player',true);
    }

    public function down()
    {
        $this->forge->dropTable('player',true);
    }
}
