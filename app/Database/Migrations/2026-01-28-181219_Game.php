<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Game extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'fbi_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'mvp' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'home_team' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'away_team' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'id_league' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'score_home' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'score_away' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'schedule' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'id_gym' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,

            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('mvp','player','id_member','CASCADE','RESTRICT');
        $this->forge->addForeignKey('home_team','team','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('away_team','team','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_league','league','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_gym','gym','id','CASCADE','RESTRICT');
        $this->forge->createTable('game',true);

    }

    public function down()
    {
        $this->forge->dropTable('game',true);
    }
}
