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
            ->with('lesson')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'action' => $activity->action,
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
            'lesson_id' => $request->lesson_id,
            'action' => $request->action,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Activity logged successfully'
        ]);
    }

}