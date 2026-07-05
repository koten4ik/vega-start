<?php

namespace Modules\ShopCart\ViewModels;

use Modules\Shop\Services\ProductService;
use Modules\Shop\ViewModels\ProductViewModel;
use Modules\ShopCart\Models\CartItemModel;

class CartItemViewModel
{
    public static function data(?CartItemModel $item): array|false
    {
        $data = [];
        if (!$item) return false;

        $data = [
            'id' => $item->id,
            'product' => ProductViewModel::data($item->product),
            'quantity' => $item->quantity,
            'price' => ProductService::formatPrice($item->price),
            'subtotal' => ProductService::formatPrice($item->subtotal()),
        ];

        return $data;
    }
}
