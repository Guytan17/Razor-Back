<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call(SettingsSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(GymSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(LicenseCodeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(RoleMemberSeeder::class);
        $this->call(ClubSeeder::class);
        $this->call(MediaSeeder::class);
    }
}
