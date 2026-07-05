<?php

namespace Modules\ShopOrder\Commands;

use Modules\ShopOrder\Models\OrderModel;
use Modules\ShopOrder\ViewModels\OrderViewModel;

class ViewOrderCommand
{
    public function execute(OrderModel $order)
    {
        return [
            'order' => OrderViewModel::data($order),
        ];
    }
}
