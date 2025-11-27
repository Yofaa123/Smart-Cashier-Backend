<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progress;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function markComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'lesson_id' => 'required|integer|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingProgress = Progress::where('user_id', $request->user_id)
            ->where('lesson_id', $request->lesson_id)
            ->first();

        if ($existingProgress) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sudah pernah diselesaikan'
            ], 200);
        }

        Progress::create([
            'user_id' => $request->user_id,
            'lesson_id' => $request->lesson_id,
            'is_completed' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Progress berhasil disimpan'
        ], 200);
    }
}
