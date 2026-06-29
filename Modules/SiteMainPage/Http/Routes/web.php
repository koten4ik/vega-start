<?php

use Illuminate\Support\Facades\Route;
use Modules\Post\Entities\PostType;
use Modules\Post\Models\TagModel;
use Modules\SiteMainPage\Filters\MainPageFilter;
use Modules\SiteMainPage\Http\Controllers\MainPageController;


Route::get('/', [MainPageController::class, 'indexPage'])->name('site.main.page');

