<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DotationTypeSeeder extends Seeder
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
        $this->db->table('dotation_type')->insertBatch($data);
    }
}
