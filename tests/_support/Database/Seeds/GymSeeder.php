<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GymSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'=> 'GYMNASE JEAN BOUCHE',
                'fbi_code' => '21730002',
                'id_address' => 1
            ],
            [
                'name'=> 'SALLE JOSETTE & PHILIPPE PINAUD',
                'fbi_code' => '21739101',
                'id_address' => 2
            ]
        ];
        $gymModel = model('GymModel');
        foreach ($data as $gym) {
            $gymModel->save($gym);
        }
    }
}