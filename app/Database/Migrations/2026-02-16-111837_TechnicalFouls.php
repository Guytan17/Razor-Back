<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TechnicalFouls extends Migration
{
    public function up()
    {
        $this->forge->addField([
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
            'id_classification' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'amount' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ]
        ]);
        $this->forge->addForeignKey('id_game', 'game', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('id_member', 'member', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_classification', 'classification', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('technical_fouls');
    }

    public function down()
    {
        $this->forge->dropTable('technical_fouls');
    }
}
