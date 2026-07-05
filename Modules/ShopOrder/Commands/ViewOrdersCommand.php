<?php

namespace Modules\ShopOrder\Commands;

use Illuminate\Support\Facades\Auth;
use Modules\ShopOrder\Models\OrderModel;
use Modules\ShopOrder\ViewModels\OrderViewModel;

class ViewOrdersCommand
{
    public function execute()
    {
        $orders = OrderModel::where('user_id', Auth::id())
            ->latest()
            ->get();

        return [
            'orders' => $orders->map(fn($order) => OrderViewModel::data($order))->all(),
        ];
    }
}
