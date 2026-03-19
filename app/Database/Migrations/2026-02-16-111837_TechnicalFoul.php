<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TechnicalFoul extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
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
            'id_classification' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_type' => [
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_game', 'game', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_member', 'member', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_classification', 'classification', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_type', 'type', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('technical_foul');
    }

    public function down()
    {
        $this->forge->dropTable('technical_foul');
    }
}
