<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'address_1'=> 'Rue François Viète',
                'address_2' => '',
                'id_city' => 1
            ],
            [
                'address_1'=> 'Rue du Gymnase',
                'address_2' => '',
                'id_city' => 2
            ]
        ];
        $addressModel = model('AddressModel');
        foreach ($data as $address) {
            $addressModel->save($address);
        }
    }
}