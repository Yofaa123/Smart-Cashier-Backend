<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\User;
use App\Models\Badge;
use App\Models\Lesson;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use DB;

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

        // Catat aktivitas menyelesaikan pelajaran
        $lesson = Lesson::find($request->lesson_id);
        if ($lesson) {
            Activity::create([
                'user_id' => $request->user_id,
                'subject_id' => $lesson->subject_id,
                'lesson_id' => $request->lesson_id,
                'action' => 'completed_lesson'
            ]);
        }

        // Add XP and level logic
        $user = User::find($request->user_id);
        $oldLevel = $user->level;
        $user->xp += 10;
        while ($user->xp >= 100) {
            $user->level += 1;
            $user->xp -= 100;
        }
        $user->save();

        // Check for new badges if level increased
        if ($user->level > $oldLevel) {
            $newBadges = Badge::where('min_level', '<=', $user->level)
                ->whereNotIn('id', function($query) use ($user) {
                    $query->select('badge_id')
                          ->from('badge_user')
                          ->where('user_id', $user->id);
                })
                ->get();

            foreach ($newBadges as $badge) {
                DB::table('badge_user')->insert([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Progress berhasil disimpan'
        ], 200);
    }
}
