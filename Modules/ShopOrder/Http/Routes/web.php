<?php

use Illuminate\Support\Facades\Route;
use Modules\ShopOrder\Http\Controllers\OrderController;

Route::get('/checkout', [OrderController::class, 'checkoutPage'])
    ->name('shop.order.checkoutPage');
Route::post('/checkout', [OrderController::class, 'store'])
    ->name('shop.order.create');
Route::get('/checkout/success/{order}', [OrderController::class, 'successPage'])
    ->name('shop.order.success');

Route::middleware('auth')->group(function () {
    Route::get('/cabinet/orders', [OrderController::class, 'ordersPage'])
        ->name('shop.order.list');
});
