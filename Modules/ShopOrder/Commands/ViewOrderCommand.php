<?php

namespace Modules\ShopOrder\Commands;

use Modules\ShopOrder\Models\OrderModel;
use Modules\ShopOrder\ViewModels\OrderViewModel;

class ViewOrderCommand
{
    public function execute($orderId)
    {
        $order = OrderModel::findOrFail($orderId);

        return [
            'order' => OrderViewModel::data($order),
        ];
    }
}
