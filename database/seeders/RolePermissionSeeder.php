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

        // Create Default Users for Login fix
        $users = [
            ['nip' => '0001', 'nama' => 'Super Administrator', 'role' => 'Superadmin'],
            ['nip' => '1111', 'nama' => 'Admin User', 'role' => 'Admin 1'],
            ['nip' => '1234', 'nama' => 'Regular User', 'role' => 'User'],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['nip' => $u['nip']],
                [
                    'nama_lengkap' => $u['nama'],
                    'password' => Hash::make($u['nip']), 
                    'is_active' => true,
                    'must_change_password' => false, 
                ]
            );
            $user->assignRole($u['role']);
        }
    }
}
