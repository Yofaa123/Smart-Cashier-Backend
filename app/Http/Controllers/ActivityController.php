<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProgress;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activities = Activity::where('user_id', $user->id)
            ->with(['subject', 'lesson'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'action' => $activity->action,
                    'subject' => $activity->subject,
                    'lesson' => $activity->lesson,
                    'created_at' => $activity->created_at->toISOString()
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        Activity::create([
            'user_id' => auth()->id(),
            'subject_id' => $request->subject_id,
            'lesson_id' => $request->lesson_id,
            'action' => $request->action,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Activity logged successfully'
        ]);
    }

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