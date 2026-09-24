<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeasonSponsor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_season'=> [
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'id_sponsor'=> [
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'id_rank'=>[
                'type'=>'INT',
                'constraint'=>9,
                'null'=>false,
            ],
            'id_dotation_type'=>[
                'type'=>'INT',
                'constraint'=>9,
                'null'=>false,
            ],
            'dotation_amount'=>[
                'type'=>'INT',
                'constraint'=>11,
                'null'=>false,
            ],
            'specifications'=>[
                'type'=>'TEXT',
                'null'=>true,
            ],
        ]);
        $this->forge->addKey(['id_season','id_sponsor'],true);
        $this->forge->addForeignKey('id_season','season','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_sponsor','sponsor','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_rank','sponsor_rank','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_dotation_type','dotation_type','id','CASCADE','RESTRICT');
        $this->forge->createTable('season_sponsor',true);
    }

    public function down()
    {
        $this->forge->dropTable('season_sponsor');
    }
}
