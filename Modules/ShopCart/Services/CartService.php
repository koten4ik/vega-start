<?php

namespace Modules\ShopCart\Services;

use Illuminate\Support\Facades\Auth;
use Modules\ShopCart\Models\CartModel;
use Modules\User\Services\UserService;

class CartService
{
    public static function currentCart(): CartModel
    {
        if (Auth::check()) {
            return CartModel::firstOrCreate(['user_id' => Auth::id()]);
        }

        $uuid = UserService::uuid();

        return CartModel::firstOrCreate(['uuid' => $uuid, 'user_id' => null]);
    }

    public static function itemsCount(): int
    {
        $cart = Auth::check()
            ? CartModel::where('user_id', Auth::id())->first()
            : CartModel::where('uuid', UserService::uuid())->first();

        return $cart ? $cart->itemsCount() : 0;
    }
}
