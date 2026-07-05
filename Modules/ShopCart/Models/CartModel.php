<?php

namespace Modules\ShopCart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartModel extends Model
{
    protected $table = 'shop_carts';

    protected $fillable = [
        'user_id',
        'uuid',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItemModel::class, 'cart_id');
    }

    public function total(): float
    {
        return (float)$this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function itemsCount(): int
    {
        return (int)$this->items->sum('quantity');
    }
}
