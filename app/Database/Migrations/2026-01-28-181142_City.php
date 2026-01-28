<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class City extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'zip_code' => [
                'type' => 'INT',
                'constraint' => 5,
                'null' => false,
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'department_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'department_number' => [
                'type' => 'VARCHAR',
                'constraint' =>3,
                'null' => true,
            ],
            'region_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id',true);
        $this->forge->createTable('city',true);
    }

    public function down()
    {
        $this->forge->dropTable('city',true);
    }
}
