<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LessonProgress;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function complete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|integer|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $progress = LessonProgress::firstOrNew([
            'user_id' => $user->id,
            'lesson_id' => $request->lesson_id,
        ]);

        $progress->status = 'completed';
        $progress->completed_at = now();
        $progress->save();

        // Catat activity
        Activity::create([
            'user_id' => $user->id,
            'lesson_id' => $request->lesson_id,
            'action' => 'complete_lesson',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson marked as completed'
        ]);
    }
}