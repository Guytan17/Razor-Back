<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleMemberSeeder extends Seeder
{
    public function run()
    {
        $rmm = model('RoleMemberModel');

        for($i = 1; $i < 101; $i++) {
            $dataRoleMember = [
                'id_member' => $i,
                'id_role' => 2,
            ];
            $rmm->insert($dataRoleMember);
        }
    }
}
