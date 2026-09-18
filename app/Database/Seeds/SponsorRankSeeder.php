<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SponsorRankSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'rank' => '1',
                'label' => 'Très gros sponsor'
            ],
            [
                'rank' => '2',
                'label' => 'Gros sponsor'
            ],
            [
                'rank' => '3',
                'label' => 'Sponsor moyen'
            ],
            [
                'rank' => '4',
                'label' => 'Petit sponsor'
            ],

        ];
        $this->db->table('sponsor_rank')->insertBatch($data);
    }
}
