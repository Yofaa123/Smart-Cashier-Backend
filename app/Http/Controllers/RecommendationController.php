<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function recommend()
    {
        $user = Auth::user();
        $completedCount = UserProgress::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();

        if ($completedCount < 2) {
            $level = 'dasar';
            $reason = 'User masih pemula, rekomendasikan lesson dasar';
        } elseif ($completedCount < 5) {
            $level = 'menengah';
            $reason = 'User sudah menyelesaikan beberapa lesson dasar, rekomendasikan menengah';
        } else {
            $level = 'lanjut';
            $reason = 'User sudah mahir, rekomendasikan lesson lanjutan';
        }

        $lessons = Lesson::where('level', $level)->get();

        return response()->json([
            'status' => true,
            'recommendations' => $lessons,
            'reason' => $reason
        ]);
    }
}
