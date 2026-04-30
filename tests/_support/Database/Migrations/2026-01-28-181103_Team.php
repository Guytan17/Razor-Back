<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Team extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=> [
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'name'=> [
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=>false,
            ],
            'slug' => [
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=>false,
                'unique'=>true,
            ],
            'id_club'=> [
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'id_season'=> [
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'id_category'=> [
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'created_at'=> [
                'type'=>'DATETIME',
                'null'=>false,
            ],
            'updated_at'=> [
                'type'=>'DATETIME',
                'null'=>true,
            ],
            'deleted_at'=> [
                'type'=>'DATETIME',
                'null'=>true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('id_club','club','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_season','season','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_category','category','id','CASCADE','RESTRICT');
        $this->forge->createTable('team',true);
    }

    public function down()
    {
        $this->forge->dropTable('team',true);
    }
}
