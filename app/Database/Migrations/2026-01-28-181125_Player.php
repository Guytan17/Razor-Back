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
