<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RankSponsor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=> [
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'rank'=> [
                'type'=>'INT',
                'constraint'=>9,
                'null'=>false,
            ],
            'label'=> [
                'type'=>'VARCHAR',
                'constraint'=>50,
                'null'=>false,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('sponsor_rank',true);
    }

    public function down()
    {
        $this->forge->dropTable('sponsor_rank',true);
    }
}
