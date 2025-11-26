<?php

use Illuminate\Support\Facades\Route;

Route::get('/check', function () {
    return ['status' => true, 'message' => 'API working'];
});
