<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TypeDotation extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=> [
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'type'=> [
                'type'=>'VARCHAR',
                'constraint'=>50,
                'null'=>false,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('dotation_type',true);
    }

    public function down()
    {
        $this->forge->dropTable('dotation_type',true);
    }
}
