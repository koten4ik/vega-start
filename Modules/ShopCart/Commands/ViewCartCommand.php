<?php

namespace Modules\ShopCart\Commands;

use Modules\ShopCart\Services\CartService;
use Modules\ShopCart\ViewModels\CartViewModel;

class ViewCartCommand
{
    public function execute()
    {
        $cart = CartService::currentCart();

        return [
            'cart' => CartViewModel::data($cart),
        ];
    }
}
