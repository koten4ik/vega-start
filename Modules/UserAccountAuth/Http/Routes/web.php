<?php

use Illuminate\Support\Facades\Route;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Services\PageService;
use Modules\UserAccountAuth\Http\Controllers\AuthController;
use Modules\UserAccountAuth\Http\Controllers\OAuthController;

Route::middleware('guest')->group(function () {
    Route::get(PageService::byModule(PageModule::LOGIN)->url ?? '/login', [AuthController::class, 'loginPage'])
        ->name('user.auth.loginPage');
    Route::post('/login', [AuthController::class, 'login'])
        ->name('user.auth.login');
});

Route::get('/loginas', [AuthController::class, 'loginas'])
    ->name('user.auth.loginas');

Route::any('/logout', [AuthController::class, 'logout'])
    ->name('user.auth.logout');




