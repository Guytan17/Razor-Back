<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LeagueTeam extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_team' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_division' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
        ]);
        $this->forge->addForeignKey('id_team','team','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_division','division','id','CASCADE','RESTRICT');
        $this->forge->createTable('division_team',true);
    }

    public function down()
    {
        $this->forge->dropTable('division_team',true);
    }
}
