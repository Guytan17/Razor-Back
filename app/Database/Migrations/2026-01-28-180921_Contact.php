<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Contact extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'entity_type' =>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>false,
            ],
            'entity_id' =>[
                'type'=>'INT',
                'constraint'=>'11',
                'null'=>false,
            ],
            'mail' =>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'null'=>true,
            ],
            'phone_number' =>[
                'type'=>'VARCHAR',
                'constraint'=>'10',
                'null'=>true,
            ],
            'details'=>[
                'type'=>'TEXT',
                'null'=>true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('contact',true);
    }

    public function down()
    {
        $this->forge->dropTable('contact',true);
    }
}
