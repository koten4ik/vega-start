<?php

use Illuminate\Support\Facades\Route;
use Modules\ShopCart\Http\Controllers\CartController;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Services\PageService;

Route::get(PageService::byModule(PageModule::CART)->url ?? '/cart', [CartController::class, 'cartPage'])
    ->name('shop.cart.page');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('shop.cart.add');

Route::post('/cart/update', [CartController::class, 'update'])
    ->name('shop.cart.update');

Route::post('/cart/remove', [CartController::class, 'remove'])
    ->name('shop.cart.remove');
