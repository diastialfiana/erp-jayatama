<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Roles
        $roles = [
            'Superadmin',
            'Admin 1',
            'Admin 2',
            'User'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Default Superadmin
        $superadmin = User::firstOrCreate(
            ['username' => '0001'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('0001'), // Default password
                'status' => 'active',
                'role' => 'super_admin',
                'must_change_password' => true,
            ]
        );

        // Assign Role
        $superadmin->assignRole('Superadmin');
    }
}
