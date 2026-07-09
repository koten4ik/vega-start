<?php

use Illuminate\Support\Facades\Route;
use Modules\SiteMainPage\Http\Controllers\MainPageController;


Route::get('/', [MainPageController::class, 'indexPage'])->name('site.main.page');

