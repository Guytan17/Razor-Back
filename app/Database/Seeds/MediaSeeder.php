<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'file_path'=> 'uploads/logos/TBLR-logo/TBLR-logo.png',
                'entity_id'=>1,
                'entity_type'=>'settings',
                'title'=>'Logo du site',
                'alt'=>'Razor-Back',
            ]
        ];

        $this->db->table('media')->insertBatch($data);
    }
}
