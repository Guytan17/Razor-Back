<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call('SettingsSeeder');
        $this->call('RoleSeeder');
        $this->call('LicenseCodeSeeder');
        $this->call('CategorySeeder');
        $this->call('MemberSeeder');
        $this->call('MediaSeeder');
    }
}
