<?php

namespace Modules\ShopCatalog\Commands;

use Modules\Shop\Queries\ProductBySlugQuery;

class ViewProductCommand
{
    public function execute($slug)
    {
        $product = ProductBySlugQuery::get($slug)->firstOrFail();

        return [
            'product' => $product,
        ];
    }
}
