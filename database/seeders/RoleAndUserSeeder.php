<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::create(['name' => 'superadmin']);
        $adminRole = Role::create(['name' => 'admin']);

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'nip' => 'admin123'
        ]);

        $user->assignRole($superAdminRole);
    }
}
