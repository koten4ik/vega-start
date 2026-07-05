<?php

namespace Modules\Shop\Services;

class ProductService
{
    public static function formatPrice($price): string
    {
        return number_format((float)$price, 2, '.', ' ') . ' ₽';
    }

    public static function isInStock($product, int $quantity = 1): bool
    {
        return $product !== null && $product->is_active && $product->quantity >= $quantity;
    }
}
