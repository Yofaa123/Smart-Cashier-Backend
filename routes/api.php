<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;

Route::get('/subjects', [SubjectController::class, 'index']);

Route::get('/check', function () {
    return ['status' => true, 'message' => 'API working'];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
