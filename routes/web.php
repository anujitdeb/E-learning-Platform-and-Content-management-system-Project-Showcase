<?php

use App\Http\Controllers\Authentication\SocialiteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

//Google Authentication
Route::prefix('user/auth')->group(function () {
    Route::get('/google-callback', [SocialiteController::class, 'googleAuthenticationCallBack']);
});
