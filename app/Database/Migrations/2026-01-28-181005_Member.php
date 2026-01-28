<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Member extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'auto_increment'=>true,
            ],
            'first_name' => [
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
            ],
            'last_name' => [
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
            ],
            'slug'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
                'unique'=>true,
            ],
            'date_of_birth'=>[
                'type'=>'DATE',
                'null'=>false,
            ],
            'id_license_code'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>true,
            ],
            'license_status'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'balance'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>true,
            ],
            'technicals_fouls_this_season'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>true,
            ],
            'id_role'=>[
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
            ],
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('id_role','role','id','CASCADE','CASCADE');
        $this->forge->createTable('member',true);
    }

    public function down()
    {
        $this->forge->dropTable('member',true);
    }
}
