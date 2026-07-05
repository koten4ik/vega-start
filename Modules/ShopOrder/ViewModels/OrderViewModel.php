<?php

namespace Modules\ShopOrder\ViewModels;

use Modules\Shop\Services\ProductService;
use Modules\ShopOrder\Models\OrderModel;

class OrderViewModel
{
    public static function data(?OrderModel $order): array|false
    {
        $data = [];
        if (!$order) return false;

        $data = [
            'id' => $order->id,
            'status' => $order->status->label(),
            'total' => ProductService::formatPrice($order->total),
            'created_at' => $order->created_at->format('d.m.Y'),
        ];

        return $data;
    }
}
