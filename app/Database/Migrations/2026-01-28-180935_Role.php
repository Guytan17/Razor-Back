<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Role extends Migration
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
            ],
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('role',true);
    }

    public function down()
    {
        $this->forge->dropTable('role',true);
    }
}
