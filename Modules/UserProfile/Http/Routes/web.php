<?php

use Illuminate\Support\Facades\Route;
use Modules\UserProfile\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profilePage'])
        ->name('user.cabinet');
    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('user.profile.update');
});
