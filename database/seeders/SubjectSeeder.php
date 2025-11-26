<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Matematika', 'description' => 'Belajar dasar dan lanjutan matematika'],
            ['name' => 'Bahasa Inggris', 'description' => 'Grammar, vocabulary, dan speaking'],
            ['name' => 'IPA', 'description' => 'Ilmu Pengetahuan Alam dasar'],
            ['name' => 'Teknologi', 'description' => 'Dasar pemrograman dan komputer']
        ];

        Subject::insert($subjects);
    }
}
