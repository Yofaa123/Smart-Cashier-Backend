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

Route::get('/subjects', [SubjectController::class, 'index']);

Route::get('/subjects/{id}/lessons', [LessonController::class, 'bySubject']);

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

