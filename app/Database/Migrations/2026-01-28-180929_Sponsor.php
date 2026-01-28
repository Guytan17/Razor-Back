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
                'constraint'=>255,
                'null'=>false,
            ],
            'slug'=> [
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=>false,
                'unique'=>true,
            ],
            'rank'=>[
                'type'=>'INT',
                'constraint'=>1,
                'null'=>true,
            ],
            'specifications'=>[
                'type'=>'TEXT',
                'null'=>true,
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
