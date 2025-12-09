<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\FavoriteLessonController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\BookmarkController;

Route::get('/subjects', [SubjectController::class, 'index']);

Route::get('/subjects/{id}/lessons', [LessonController::class, 'bySubject']);

Route::middleware('auth:sanctum')->get('/lessons/{id}', [LessonController::class, 'show']);
Route::middleware('auth:sanctum')->get('/lessons/{id}/content', [LessonController::class, 'getContent']);
Route::get('/lessons/{id}/predict-difficulty', [LessonController::class, 'predictDifficulty']);

Route::middleware('auth:sanctum')->post('/progress/complete', [ProgressController::class, 'markComplete']);

Route::middleware('auth:sanctum')->get('/recommendations', [RecommendationController::class, 'recommend']);

Route::get('/check', function () {
    return ['status' => true, 'message' => 'API working'];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)], 200)
        : response()->json(['message' => __($status)], 400);
});

Route::post('/forgot-password/request-otp', [PasswordResetController::class, 'requestOtp']);
Route::post('/forgot-password/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/forgot-password/reset', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->get('/profile', [ProfileController::class, 'getProfile']);
Route::middleware('auth:sanctum')->post('/profile/update', [ProfileController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->get('/activity/recent', [ActivityController::class, 'recent']);
Route::middleware('auth:sanctum')->get('/activities', [ActivityController::class, 'index']);
Route::middleware('auth:sanctum')->post('/activities', [ActivityController::class, 'store']);

Route::middleware('auth:sanctum')->post('/favorite/add', [FavoriteLessonController::class, 'addFavorite']);
Route::middleware('auth:sanctum')->post('/favorite/remove', [FavoriteLessonController::class, 'removeFavorite']);
Route::middleware('auth:sanctum')->get('/favorite/list', [FavoriteLessonController::class, 'listFavorites']);
Route::middleware('auth:sanctum')->get('/gamification/status', [GamificationController::class, 'getStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookmarks/subjects', [BookmarkController::class, 'getSubjectBookmarks']);
    Route::get('/bookmarks/lessons', [BookmarkController::class, 'getLessonBookmarks']);
    Route::post('/bookmarks', [BookmarkController::class, 'addBookmark']);
    Route::delete('/bookmarks/{id}', [BookmarkController::class, 'removeBookmark']);
});



