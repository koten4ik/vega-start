<?php

namespace Modules\Shop\Queries;

use Modules\Shop\Models\ProductModel;

class ProductsByCategoryQuery
{
    public static function get($categorySlug = null)
    {
        $query = ProductModel::display();

        if ($categorySlug !== null) {
            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        return $query;
    }
}
