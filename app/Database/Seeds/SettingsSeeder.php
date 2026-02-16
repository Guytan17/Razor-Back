<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
              'class' => 'Config\App',
              'key' => 'siteName',
              'value' => 'Razor-Back',
              'type' => 'string',
            ],
            [
                'class' => 'Config\App',
                'key' => 'contactEmail',
                'value' => 'admin@admin.fr',
                'type' => 'string',
            ],
            [
                'class' => 'Config\App',
                'key' => 'siteLogoId',
                'value' => '1',
                'type' => 'integer',
            ]
        ];

        $this->db->table('settings')->insertBatch($data);
    }
}
