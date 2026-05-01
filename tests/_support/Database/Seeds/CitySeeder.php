<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'zip_code'=> '17000',
                'label' => 'la rochelle',
                'department_name' => 'charente-maritime',
                'department_number' => 17,
                'region_name' => 'nouvelle-aquitaine'
            ],
            [
                'zip_code'=> '17220',
                'label' => 'st rogatien',
                'department_name' => 'charente-maritime',
                'department_number' => 17,
                'region_name' => 'nouvelle-aquitaine'
            ]
        ];
        $cityModel = model('CityModel');
        foreach ($data as $city) {
            $cityModel->save($city);
        }
    }
}