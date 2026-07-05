<?php

namespace Modules\ShopOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\ShopOrder\Enums\OrderStatus;
use Modules\User\Models\UserModel;

class OrderModel extends Model
{
    protected $table = 'shop_orders';

    protected $fillable = [
        'user_id',
        'uuid',
        'status',
        'total',
        'name',
        'phone',
        'email',
        'address',
        'comment',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
