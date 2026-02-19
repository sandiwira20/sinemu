<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        User::create([
            'role_id'  => $adminRole->id,
            'name'     => 'Admin SiNemu',
            'email'    => 'admin@sinemu.test',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'role_id'  => $userRole->id,
            'name'     => 'Mahasiswa Contoh',
            'nim'      => '24050001',
            'email'    => 'user@sinemu.test',
            'password' => bcrypt('password'),
        ]);
    }
}