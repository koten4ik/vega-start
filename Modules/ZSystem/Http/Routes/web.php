<?php

use Illuminate\Support\Facades\Route;
use Modules\ZSystem\Http\Controllers\SystemController;
use Modules\ZSystem\Http\Controllers\TempController;



Route::get('/temp/t', [TempController::class, 'temp']);

Route::get('/system/count', [SystemController::class, 'countDiagram'])->middleware('auth');
Route::get('/system/count2', [SystemController::class, 'countDiagram2'])->middleware('auth');
Route::get('/system/count3', [SystemController::class, 'countDiagram3'])->middleware('auth');
Route::get('/system/post', [SystemController::class, 'testPost'])->middleware('auth');
Route::get('/system/update', [SystemController::class, 'update'])->middleware('auth');

Route::any('/system/mailSend', [SystemController::class, 'mailSend'])->middleware('auth');
Route::any('/system/mailBody', [SystemController::class, 'mailBody'])->middleware('auth');
