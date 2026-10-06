<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sponsor extends Migration
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
                'constraint'=>50,
                'null'=>false,
            ],
            'slug'=> [
                'type'=>'VARCHAR',
                'constraint'=>50,
                'null'=>false,
                'unique'=>true,
            ],
            'slogan'=> [
                'type'=>'VARCHAR',
                'constraint'=>150,
                'null'=>false,
                'unique'=>true,
            ],
            'comments'=>[
                'type'=>'TEXT',
                'null'=>true,
            ],
            'created_at'=>[
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at'=>[
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at'=>[
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('sponsor',true);
    }

    public function down()
    {
        $this->forge->dropTable('sponsor',true);
    }
}
