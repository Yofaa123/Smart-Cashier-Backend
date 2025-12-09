<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Subject;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    public function getSubjectBookmarks()
    {
        $user = auth()->user();

        $bookmarks = Bookmark::where('user_id', $user->id)
            ->whereNotNull('subject_id')
            ->with('subject')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Subject bookmarks retrieved successfully',
            'data' => $bookmarks->map(function ($bookmark) {
                return [
                    'id' => $bookmark->id,
                    'subject' => $bookmark->subject,
                    'created_at' => $bookmark->created_at,
                ];
            })
        ]);
    }

    public function getLessonBookmarks()
    {
        $user = auth()->user();
        $bookmarks = Bookmark::where('user_id', $user->id)
            ->whereNotNull('lesson_id')
            ->with(['lesson.subject'])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Lesson bookmarks retrieved successfully',
            'data' => $bookmarks->map(function ($bookmark) {
                return [
                    'id' => $bookmark->id,
                    'lesson' => $bookmark->lesson,
                    'subject' => $bookmark->lesson->subject,
                    'created_at' => $bookmark->created_at,
                ];
            })
        ]);
    }

    public function addBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $subjectId = $request->input('subject_id');
        $lessonId = $request->input('lesson_id');

        if (!$subjectId && !$lessonId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Either subject_id or lesson_id must be provided'
            ], 422);
        }

        if ($subjectId && $lessonId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot bookmark both subject and lesson at the same time'
            ], 422);
        }

        $user = auth()->user();

        // Check for existing bookmark
        $existing = Bookmark::where('user_id', $user->id)
            ->where(function ($query) use ($subjectId, $lessonId) {
                if ($subjectId) {
                    $query->where('subject_id', $subjectId);
                } else {
                    $query->where('lesson_id', $lessonId);
                }
            })
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bookmark already exists'
            ], 409);
        }

        $bookmark = Bookmark::create([
            'user_id' => $user->id,
            'subject_id' => $subjectId,
            'lesson_id' => $lessonId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Bookmark added successfully',
            'data' => $bookmark
        ], 201);
    }

    public function removeBookmark($id)
    {
        $user = auth()->user();
        $bookmark = Bookmark::where('id', $id)->where('user_id', $user->id)->first();

        if (!$bookmark) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bookmark not found'
            ], 404);
        }

        $bookmark->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bookmark removed successfully'
        ]);
    }
}
