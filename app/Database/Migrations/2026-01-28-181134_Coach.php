<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Coach extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_member' =>[
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_team' =>[
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'main_team' => [
                'type' => 'BOOLEAN',
                'null' => false,
            ]
        ]);
        $this->forge->addForeignKey('id_member','member','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_team','team','id','CASCADE','RESTRICT');
        $this->forge->createTable('coach',true);
    }

    public function down()
    {
        $this->forge->dropTable('coach',true);
    }
}
