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
            'id_rank'=>[
                'type'=>'INT',
                'constraint'=>9,
                'null'=>true,
            ],
            'id_dotation_type'=>[
                'type'=>'INT',
                'constraint'=>9,
                'null'=>false,
            ],
            'dotation_amount'=>[
                'type'=>'INT',
                'constraint'=>9,
                'null'=>false,
            ],
            'specifications'=>[
                'type'=>'TEXT',
                'null'=>true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('rank','rank_sponsor','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('dotation_type','type_dotation','id','CASCADE','RESTRICT');
        $this->forge->createTable('sponsor',true);
    }

    public function down()
    {
        $this->forge->dropTable('sponsor',true);
    }
}
