<?php

use Illuminate\Support\Facades\Route;
use Modules\SitePage\Enums\PageDomain;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Http\Controllers\PageController;
use Modules\SitePage\Models\PageModel;

$pages = PageModel::display()
    //todo PageModule - энум сделать через статитк
    //->where('module',PageModule::TEXT_PAGE)
    ->get();
$arr = [];
foreach ($pages as $elem){
    $arr[] = $elem->url.'-'.$elem->id;
    if(strpos($elem->url,'/')==false)
        Route::get($elem->url, [PageController::class, 'viewPage']);
}
