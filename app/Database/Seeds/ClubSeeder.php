<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code' => 'NAQ0017026',
                'name' => 'Tasdon Basket La Rochelle',
                'color_1' => 'orange',
                'color_2' => 'noir',
            ]
        ];
        $clubModel = model('ClubModel');
        foreach ($data as $club) {
            $clubModel->save($club);
        }
    }
}
