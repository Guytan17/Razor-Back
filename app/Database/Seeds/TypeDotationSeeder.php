<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeDotationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'type' => 'Argent',
            ],
            [
                'type' => 'Matériel',
            ],
            [
                'type' => 'Réductions/bons-cadeaux',
            ],
            [
                'type' => 'Main d\'oeuvre',
            ],


        ];
        $this->db->table('type_dotation')->insertBatch($data);
    }
}
