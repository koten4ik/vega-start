<?php

namespace Modules\ShopCart\Commands;

use Modules\ShopCart\Models\CartItemModel;
use Modules\ShopCart\Services\CartService;

class UpdateCartItemCommand
{
    public function execute($data): bool
    {
        $cart = CartService::currentCart();

        $item = CartItemModel::where('cart_id', $cart->id)
            ->where('id', $data['cart_item_id'])
            ->firstOrFail();

        if ((int)$data['quantity'] <= 0) {
            $item->delete();
            return true;
        }

        $item->quantity = (int)$data['quantity'];
        $item->save();

        return true;
    }
}
