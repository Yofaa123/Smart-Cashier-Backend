<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteLesson;
use Illuminate\Support\Facades\Validator;

class FavoriteLessonController extends Controller
{
    public function addFavorite(Request $request)
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

        $existing = FavoriteLesson::where('user_id', $user->id)
            ->where('lesson_id', $request->lesson_id)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lesson already favorited'
            ], 200);
        }

        FavoriteLesson::create([
            'user_id' => $user->id,
            'lesson_id' => $request->lesson_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson added to favorites'
        ], 200);
    }

    public function removeFavorite(Request $request)
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

        FavoriteLesson::where('user_id', $user->id)
            ->where('lesson_id', $request->lesson_id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson removed from favorites'
        ], 200);
    }

    public function listFavorites(Request $request)
    {
        $user = $request->user();

        $favorites = FavoriteLesson::where('user_id', $user->id)
            ->join('lessons', 'favorite_lessons.lesson_id', '=', 'lessons.id')
            ->join('subjects', 'lessons.subject_id', '=', 'subjects.id')
            ->select(
                'lessons.id',
                'lessons.title',
                'lessons.content',
                'lessons.level',
                'subjects.name as subject_name'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Favorites retrieved successfully',
            'favorites' => $favorites
        ], 200);
    }
}