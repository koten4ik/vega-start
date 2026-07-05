<?php

namespace Modules\ShopCatalog\Commands;

use Modules\Shop\Models\CategoryModel;
use Modules\Shop\Queries\ProductsByCategoryQuery;

class ViewCatalogCommand
{
    public function execute($categorySlug = null)
    {
        $products = ProductsByCategoryQuery::get($categorySlug)
            ->latest('id')
            ->paginate(20);

        $categories = CategoryModel::orderDef()->get();

        return [
            'products' => $products,
            'categories' => $categories,
            'category_slug' => $categorySlug,
        ];
    }
}
