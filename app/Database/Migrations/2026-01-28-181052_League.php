<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class League extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
            ],
            'id_season'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'id_category'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'created_at'=>[
                'type'=>'DATETIME',
                'null'=>false,
            ],
            'updated_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ],
            'deleted_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('id_season','season','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_category','category','id','CASCADE','RESTRICT');
        $this->forge->createTable('league',true);
    }

    public function down()
    {
        $this->forge->dropTable('league',true);
    }
}
