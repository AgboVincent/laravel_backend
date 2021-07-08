<?php

use App\Http\Controllers\Authentication\Login;
use App\Http\Controllers\User\Profile;
use Illuminate\Support\Facades\Route;

Route::post('authentication/login', Login::class)
    ->name('auth.login');
Route::get('profile', Profile::class)
    ->middleware('auth')
    ->name('profile');
