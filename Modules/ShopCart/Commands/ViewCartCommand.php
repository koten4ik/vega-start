<?php

namespace Modules\ShopCart\Commands;

use Modules\ShopCart\Services\CartService;

class ViewCartCommand
{
    public function execute()
    {
        $cart = CartService::currentCart();

        return [
            'cart' => $cart,
        ];
    }
}
