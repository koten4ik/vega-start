<?php

use Illuminate\Support\Facades\Route;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Http\Controllers\PageController;
use Modules\SitePage\Models\PageModel;

$pages = PageModel::display()
    //todo PageModule - энум сделать через статитк
    //->where('module',PageModule::TEXT_PAGE)
    ->get();

foreach ($pages as $elem) {
    if ($elem->slug && strpos($elem->slug, '/') === false) {
        Route::get($elem->slug, [PageController::class, 'viewPage'])
            ->name('page.' . $elem->slug);
    }
}
