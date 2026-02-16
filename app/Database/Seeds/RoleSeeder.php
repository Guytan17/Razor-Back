<?php

namespace App\Database\Seeds;

use App\Models\RoleModel;
use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'=>'Président',
            ],
            [
                'name'=>'Joueur'
            ],
            [
                'name'=>'Coach'
            ],
            [
                'name'=>'Trésorier'
            ],
            [
                'name'=>'Parent référent'
            ]
        ];

        $roleModel = model('RoleModel');

       foreach ($data as $role) {
           $roleModel->save($role);
       }
    }
}
