<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\Lesson;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User demo
        User::firstOrCreate(
            ['email' => 'demo@gmail.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password')
            ]
        );

        // Subjects
        $math = Subject::firstOrCreate([
            'name' => 'Matematika',
            'description' => 'Belajar dasar dan lanjutan matematika'
        ]);

        $english = Subject::firstOrCreate([
            'name' => 'Bahasa Inggris',
            'description' => 'Grammar, vocabulary, speaking'
        ]);

        // Lessons Matematika
        Lesson::firstOrCreate([
            'subject_id' => $math->id,
            'title' => 'Penjumlahan Dasar',
            'content' => 'Pelajari dasar-dasar penjumlahan angka.',
            'level' => 'Dasar'
        ]);

        Lesson::firstOrCreate([
            'subject_id' => $math->id,
            'title' => 'Pengurangan Dasar',
            'content' => 'Pelajari dasar-dasar pengurangan.',
            'level' => 'Dasar'
        ]);

        // Lessons Inggris
        Lesson::firstOrCreate([
            'subject_id' => $english->id,
            'title' => 'Basic Vocabulary',
            'content' => 'Learn common English words.',
            'level' => 'Dasar'
        ]);
    }
}
