<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Matematika' => [
                ['title' => 'Penjumlahan Dasar', 'content' => 'Pelajari dasar-dasar penjumlahan angka.', 'level' => 'dasar'],
                ['title' => 'Aljabar Menengah', 'content' => 'Pengenalan variabel dan persamaan aljabar.', 'level' => 'menengah'],
            ],
            'Bahasa Inggris' => [
                ['title' => 'Basic Grammar', 'content' => 'Pelajari tenses dasar dan struktur kalimat.', 'level' => 'dasar'],
                ['title' => 'Daily Conversation', 'content' => 'Latihan percakapan sehari-hari.', 'level' => 'menengah'],
            ],
            'IPA' => [
                ['title' => 'Struktur Sel', 'content' => 'Pengenalan komponen sel dan fungsinya.', 'level' => 'dasar'],
                ['title' => 'Sistem Pernapasan', 'content' => 'Cara kerja sistem pernapasan manusia.', 'level' => 'menengah'],
            ],
            'Teknologi' => [
                ['title' => 'Pengantar Komputer', 'content' => 'Dasar-dasar komponen komputer.', 'level' => 'dasar'],
                ['title' => 'Dasar Pemrograman', 'content' => 'Pengenalan bahasa pemrograman dasar.', 'level' => 'menengah'],
            ],
        ];

        foreach ($subjects as $subjectName => $lessons) {
            $subject = Subject::where('name', $subjectName)->first();
            if ($subject) {
                foreach ($lessons as $lessonData) {
                    Lesson::create([
                        'subject_id' => $subject->id,
                        'title' => $lessonData['title'],
                        'content' => $lessonData['content'],
                        'level' => $lessonData['level'],
                    ]);
                }
            }
        }
    }
}
