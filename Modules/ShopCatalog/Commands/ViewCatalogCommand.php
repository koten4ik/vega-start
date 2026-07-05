<?php

namespace Modules\ShopCatalog\Commands;

use Modules\Shop\Models\CategoryModel;
use Modules\Shop\Queries\ProductsByCategoryQuery;
use Modules\Shop\ViewModels\CategoryViewModel;
use Modules\Shop\ViewModels\ProductViewModel;

class ViewCatalogCommand
{
    public function execute($categorySlug = null)
    {
        $products = ProductsByCategoryQuery::get($categorySlug)
            ->latest('id')
            ->paginate(20)
            ->through(fn($product) => ProductViewModel::data($product));

        $categories = CategoryModel::orderDef()->get()
            ->map(fn($category) => CategoryViewModel::data($category))
            ->all();

        return [
            'products' => $products,
            'categories' => $categories,
            'category_slug' => $categorySlug,
        ];
    }
}
