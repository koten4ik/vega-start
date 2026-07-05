<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ZSupport\App\Models\VegaModel;

class ProductModel extends VegaModel
{
    protected $table = 'shop_products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'image',
        'display',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'display' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('quantity', '>', 0);
    }
}
