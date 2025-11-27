<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use DB;

class GamificationController extends Controller
{
    public function getStatus(Request $request)
    {
        $user = $request->user();

        $badges = DB::table('badge_user')
            ->join('badges', 'badge_user.badge_id', '=', 'badges.id')
            ->where('badge_user.user_id', $user->id)
            ->select('badges.name', 'badges.description', 'badges.icon')
            ->get();

        return response()->json([
            'status' => 'success',
            'level' => $user->level,
            'xp' => $user->xp,
            'badges' => $badges
        ], 200);
    }
}