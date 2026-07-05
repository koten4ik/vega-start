<?php

namespace Modules\ShopCatalog\Commands;

use Modules\Shop\Queries\ProductBySlugQuery;
use Modules\Shop\ViewModels\ProductViewModel;

class ViewProductCommand
{
    public function execute($slug)
    {
        $product = ProductBySlugQuery::get($slug)->firstOrFail();

        return [
            'product' => ProductViewModel::data($product),
        ];
    }
}
