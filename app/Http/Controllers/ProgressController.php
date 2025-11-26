<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function markComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|integer|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        UserProgress::updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $request->lesson_id],
            ['completed_at' => now()]
        );

        return response()->json([
            'status' => true,
            'message' => 'Lesson completed'
        ]);
    }
}
