<?php

namespace Modules\Shop\Queries;

use Modules\Shop\Models\ProductModel;

class ProductBySlugQuery
{
    public static function get($slug)
    {
        return ProductModel::display()->where('slug', $slug);
    }
}
