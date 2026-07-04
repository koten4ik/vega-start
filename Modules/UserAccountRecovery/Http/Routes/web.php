<?php

use Illuminate\Support\Facades\Route;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Services\PageService;
use Modules\UserAccountRecovery\Http\Controllers\RecoveryController;

Route::middleware('guest')->group(function () {

    Route::get(PageService::byModule(PageModule::PASSWORD_RESET)->url ?? '/password', [RecoveryController::class, 'recoveryPage'])
        ->name('user.password.recoveryPage');
    Route::post('/password', [RecoveryController::class, 'sendRecoveryLink'])
        ->name('user.password.recovery');

    Route::get('/password/change', [RecoveryController::class, 'changePasswordPage'])
        ->name('user.password.changePage');
    Route::post('/password/change', [RecoveryController::class, 'changePassword'])
        ->name('user.password.change');
});
