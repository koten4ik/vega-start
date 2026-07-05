<?php

namespace Modules\ShopCart\Commands;

use Modules\Shop\Models\ProductModel;
use Modules\ShopCart\Services\CartService;

class AddToCartCommand
{
    public function execute($data): bool
    {
        $product = ProductModel::findOrFail($data['product_id']);
        $cart = CartService::currentCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += (int)$data['quantity'];
            $item->price = $product->price;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => (int)$data['quantity'],
                'price' => $product->price,
            ]);
        }

        return true;
    }
}
