<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
