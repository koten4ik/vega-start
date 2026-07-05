<?php

namespace Modules\ShopOrder\Commands;

use Illuminate\Support\Facades\Auth;
use Modules\ShopOrder\Models\OrderModel;

class ViewOrdersCommand
{
    public function execute()
    {
        $orders = OrderModel::where('user_id', Auth::id())
            ->latest()
            ->get();

        return [
            'orders' => $orders,
        ];
    }
}
