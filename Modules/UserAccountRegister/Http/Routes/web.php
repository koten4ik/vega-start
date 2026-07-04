<?php

use Illuminate\Support\Facades\Route;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Services\PageService;
use Modules\UserAccountRegister\Http\Controllers\ReferralController;
use Modules\UserAccountRegister\Http\Controllers\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get(PageService::byModule(PageModule::REGISTRATION)->url ?? '/registration', [RegisterController::class, 'registerPage'])->name('user.register');
    Route::post('/registration', [RegisterController::class, 'registerStore']);
});






