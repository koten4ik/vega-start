<?php

namespace Modules\ShopCart\ViewModels;

use Modules\Shop\Services\ProductService;
use Modules\ShopCart\Models\CartModel;

class CartViewModel
{
    public static function data(?CartModel $cart): array|false
    {
        $data = [];
        if (!$cart) return false;

        $data = [
            'id' => $cart->id,
            'items' => $cart->items->map(fn($item) => CartItemViewModel::data($item))->all(),
            'is_empty' => $cart->items->isEmpty(),
            'total' => ProductService::formatPrice($cart->total()),
        ];

        return $data;
    }
}
