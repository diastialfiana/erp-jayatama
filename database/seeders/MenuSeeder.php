<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['name' => 'Dashboard', 'route' => 'dashboard'],
            ['name' => 'Inventory & GA', 'route' => 'inventory.*'],
            ['name' => 'Finance', 'route' => 'finance.*'],
            ['name' => 'Accounting', 'route' => 'accounting.*'],
            ['name' => 'Administrator', 'route' => 'administrator.*'],
            ['name' => 'Panduan Penggunaan', 'route' => 'help.*'],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::firstOrCreate(['name' => $menu['name']], ['route' => $menu['route']]);
        }
    }
}
