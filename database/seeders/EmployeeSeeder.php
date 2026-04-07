<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $e1 = \App\Models\Employee::create([
            'code' => '000002',
            'nip' => '120500017',
            'full_name' => 'MADE KARMADI',
            'nick_name' => 'KARMADI',
            'mobile' => '08123456789',
            'position' => 'Widyaiswara',
            'work_at' => 'PT. JASA SWADAYA UTAMA',
            'location' => 'Widyaiswara',
            'join_date' => '2012-05-01',
            'clothes_size' => 'M',
            'pants_size' => '32',
            'email' => 'made.karmadi@email.com',
            'is_active' => true,
            'id_card_print' => false,
        ]);

        $files = [
            'SURAT LAMARAN KERJA',
            'DAFTAR RIWAYAT HIDUP',
            'FOTO 4x6 (2 LEMBAR)',
            'FOTO COPY KTP',
            'FOTO COPY NPWP',
            'FOTO COPY IJAZAH (MIN. SMA / SEDERAJAT)',
            'FOTO COPY SURAT NIKAH',
            'FOTO COPY KARTU KELUARGA',
            'FOTO COPY AKTE KELAHIRAN ANAK',
            'FOTO COPY SKCK',
            'FOTO COPY SURAT REFERENSI KERJA',
            'SURAT DOMISILI RT/RW (ASLI)',
            'SURAT DOKTER ASLI'
        ];

        foreach ($files as $f) {
            $e1->files()->create([
                'description' => $f,
                'is_checked' => rand(0, 1)
            ]);
        }

        // Add attributes for e1
        $e1->attributes()->createMany([
            ['date' => '2024-01-15', 'user_no' => 'USR-001', 'attribute_name' => 'OUTSTANDING PERFORMANCE'],
            ['date' => '2024-03-20', 'user_no' => 'USR-002', 'attribute_name' => 'PROMOTED TO SENIOR'],
        ]);

        // Add more mock employees
        \App\Models\Employee::factory(20)->create()->each(function ($emp) use ($files) {
            foreach ($files as $f) {
                $emp->files()->create([
                    'description' => $f,
                    'is_checked' => rand(0, 1)
                ]);
            }
            
            // Random attributes
            $emp->attributes()->create([
                'date' => now()->subDays(rand(1, 300)),
                'user_no' => 'USR-' . rand(100, 999),
                'attribute_name' => 'SAMPLE ATTRIBUTE ' . rand(1, 100)
            ]);
        });
    }
}
