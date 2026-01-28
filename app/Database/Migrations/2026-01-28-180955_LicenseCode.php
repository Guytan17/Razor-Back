<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LicenseCode extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'code'=>[
                'type'=>'VARCHAR',
                'constraint'=>'9',
                'null'=>false,
            ],
            'explanation'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('license_code',true);
    }

    public function down()
    {
        $this->forge->dropTable('license_code',true);
    }
}
