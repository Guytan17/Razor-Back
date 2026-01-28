<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Club extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'slug'=> [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'unique' => true,
            ],
            'color_1'=>[
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'color_2'=>[
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
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
        $this->forge->createTable('club',true);
    }

    public function down()
    {
        $this->forge->dropTable('club',true);
    }
}
