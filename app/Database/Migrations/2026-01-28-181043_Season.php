<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Season extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null'=>false,
            ],
            'start_date' =>[
                'type' => 'DATE',
                'null'=>true,
            ],
            'end_date' =>[
                'type' => 'DATE',
                'null'=>true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('season',true);
    }

    public function down()
    {
        $this->forge->dropTable('season',true);
    }
}
