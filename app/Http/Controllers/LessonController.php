<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;

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
}
