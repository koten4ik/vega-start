<?php

namespace Modules\Shop\ViewModels;

use Illuminate\Support\Facades\Storage;
use Modules\Shop\Models\ProductModel;
use Modules\Shop\Services\ProductService;

class ProductViewModel
{
    public static function data(?ProductModel $product): array|false
    {
        $data = [];
        if (!$product) return false;

        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => ProductService::formatPrice($product->price),
            'quantity' => $product->quantity,
            'image' => $product->image ? Storage::url($product->image) : null,
            'in_stock' => ProductService::isInStock($product),
        ];

        return $data;
    }
}
