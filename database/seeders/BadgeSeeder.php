<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::create([
            'name' => 'Pemula',
            'description' => 'Badge untuk pemula yang baru mulai belajar',
            'icon' => 'beginner.png',
            'min_level' => 1,
        ]);

        Badge::create([
            'name' => 'Pelajar Rajin',
            'description' => 'Badge untuk pelajar yang rajin belajar',
            'icon' => 'diligent.png',
            'min_level' => 3,
        ]);

        Badge::create([
            'name' => 'Master Kelas',
            'description' => 'Badge untuk master yang telah mencapai level tinggi',
            'icon' => 'master.png',
            'min_level' => 5,
        ]);
    }
}
