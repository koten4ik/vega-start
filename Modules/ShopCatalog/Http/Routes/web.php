<?php

use Illuminate\Support\Facades\Route;
use Modules\ShopCatalog\Http\Controllers\CatalogController;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Services\PageService;

Route::get(PageService::byModule(PageModule::CATALOG)->url ?? '/catalog', [CatalogController::class, 'catalogPage'])
    ->name('shop.catalog.page');

Route::get('/catalog/{categorySlug}', [CatalogController::class, 'categoryPage'])
    ->name('shop.catalog.category');

Route::get('/product/{slug}', [CatalogController::class, 'productPage'])
    ->name('shop.catalog.product');
