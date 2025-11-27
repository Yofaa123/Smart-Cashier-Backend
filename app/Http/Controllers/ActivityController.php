<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProgress;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function recent(Request $request)
    {
        $user = $request->user();

        $activities = UserProgress::where('user_id', $user->id)
            ->join('lessons', 'user_progress.lesson_id', '=', 'lessons.id')
            ->join('subjects', 'lessons.subject_id', '=', 'subjects.id')
            ->select(
                'lessons.title as lesson_title',
                'subjects.name as subject_name',
                'user_progress.completed_at'
            )
            ->orderBy('user_progress.completed_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Recent activities retrieved successfully',
            'activities' => $activities
        ], 200);
    }
}