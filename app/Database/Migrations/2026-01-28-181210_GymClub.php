<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GymClub extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_club' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_gym' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'main_gym' => [
                'type' => 'BOOLEAN',
                'null' => true,
            ]
        ]);
        $this->forge->addKey(['id_club','id_gym'],true,true);
        $this->forge->addForeignKey('id_club','club','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_gym','gym','id','CASCADE','RESTRICT');
        $this->forge->createTable('gym_club',true);
    }


    public function down()
    {
        $this->forge->dropTable('gym_club',true);
    }
}
