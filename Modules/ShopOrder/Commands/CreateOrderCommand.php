<?php

namespace Modules\ShopOrder\Commands;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ShopCart\Services\CartService;
use Modules\ShopOrder\Enums\OrderStatus;
use Modules\ShopOrder\Models\OrderModel;
use Modules\ZSupport\App\Exceptions\LogicException;

class CreateOrderCommand
{
    public function execute($data): OrderModel
    {
        $cart = CartService::currentCart();

        if ($cart->items->isEmpty()) {
            throw new LogicException('Корзина пуста');
        }

        return DB::transaction(function () use ($cart, $data) {
            $order = OrderModel::create([
                'user_id' => Auth::id(),
                'uuid' => $cart->uuid,
                'status' => OrderStatus::NEW,
                'total' => $cart->total(),
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'] ?? null,
                'comment' => $data['comment'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });
    }
}
