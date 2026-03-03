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
            ['nip' => '123456'],
            [
                'nama_lengkap' => 'Super Administrator',
                'password' => Hash::make('password123'), // Default password
                'is_active' => true,
            ]
        );

        // Assign Role
        $superadmin->assignRole('Superadmin');
    }
}
