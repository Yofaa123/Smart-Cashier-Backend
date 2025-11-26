<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProgressController;

Route::get('/subjects', [SubjectController::class, 'index']);

Route::get('/subjects/{id}/lessons', [LessonController::class, 'bySubject']);

Route::middleware('auth:sanctum')->post('/progress/complete', [ProgressController::class, 'markComplete']);

Route::get('/check', function () {
    return ['status' => true, 'message' => 'API working'];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
