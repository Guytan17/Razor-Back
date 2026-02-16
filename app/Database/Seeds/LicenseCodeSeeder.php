<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LicenseCodeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => '0C',
                'explanation' => 'Licence compétition standard sans mutation'
            ],
            [
                'code' => '0L',
                'explanation' => 'Licence loisir, pas de participation aux compétitions officielles FFBB'
            ],
            [
                'code' => '1C',
                'explanation' => 'Licence compétition avec mutation simple (joueur qui évoluait dans un autre club la saison précédente)'
            ],
            [
                'code' => '2C',
                'explanation'=>'Licence compétition avec mutation tardive, hors de la période définie par la FFBB',
            ]
        ];
        $this->db->table('license_code')->insertBatch($data);
    }
}
