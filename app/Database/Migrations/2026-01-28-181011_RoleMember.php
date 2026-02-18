<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RoleMember extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_role' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ]
        ]);
        $this->forge->addKey(['id_member','id_role'], true,true);
        $this->forge->addForeignKey('id_member','member','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_role','role','id','CASCADE','RESTRICT');
        $this->forge->createTable('role_member',true);
    }

    public function down()
    {
        $this->forge->dropTable('role_member',true);
    }
}
