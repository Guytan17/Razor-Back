<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ArticleTopic extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_article' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => false,
            ],
            'topic_type' =>  [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'topic_id' =>[
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ]
        ]);
        $this->forge->addForeignKey('id_article', 'article', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('article_topic',true);
    }

    public function down()
    {
        $this->forge->dropTable('article_topic',true);
    }
}
