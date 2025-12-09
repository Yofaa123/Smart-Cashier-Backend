<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Activity;

class LessonController extends Controller
{
    public function bySubject($subjectId)
    {
        $lessons = Lesson::where('subject_id', $subjectId)
            ->select('id', 'subject_id', 'title', 'content', 'level')
            ->get();

        return response()->json([
            'status' => true,
            'lessons' => $lessons
        ]);
    }

    public function show($id)
    {
        $lesson = Lesson::with('subject')->find($id);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found'
            ], 404);
        }

        // Catat aktivitas membuka pelajaran
        if (auth()->check()) {
            Activity::create([
                'user_id' => auth()->id(),
                'subject_id' => $lesson->subject_id,
                'lesson_id' => $lesson->id,
                'action' => 'opened_lesson'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'lesson' => $lesson
        ]);
    }

    public function getContent($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found'
            ], 404);
        }

        // Catat aktivitas membuka konten lengkap
        if (auth()->check()) {
            Activity::create([
                'user_id' => auth()->id(),
                'subject_id' => $lesson->subject_id,
                'lesson_id' => $lesson->id,
                'action' => 'opened_content'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'content' => $lesson->content
        ]);
    }

    public function predictDifficulty($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson not found'
            ], 404);
        }

        $difficulties = ['Mudah', 'Sedang', 'Sulit'];
        $difficulty = $difficulties[array_rand($difficulties)];
        $score = mt_rand(10, 90) / 100.0; // 0.1 to 0.9

        return response()->json([
            'status' => 'success',
            'difficulty' => $difficulty,
            'score' => $score
        ], 200);
    }
}
