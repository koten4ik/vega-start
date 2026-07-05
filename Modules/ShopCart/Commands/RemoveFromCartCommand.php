<?php

namespace Modules\ShopCart\Commands;

use Modules\ShopCart\Models\CartItemModel;
use Modules\ShopCart\Services\CartService;

class RemoveFromCartCommand
{
    public function execute($data): bool
    {
        $cart = CartService::currentCart();

        CartItemModel::where('cart_id', $cart->id)
            ->where('id', $data['cart_item_id'])
            ->delete();

        return true;
    }
}
