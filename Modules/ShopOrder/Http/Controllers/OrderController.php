<?php

namespace Modules\ShopOrder\Http\Controllers;

use Modules\ShopCart\Commands\ViewCartCommand;
use Modules\ShopOrder\Commands\CreateOrderCommand;
use Modules\ShopOrder\Commands\ViewOrdersCommand;
use Modules\ShopOrder\Http\Requests\CreateOrderRequest;
use Modules\ShopOrder\Models\OrderModel;
use Modules\ZSupport\App\Controllers\VegaController;

class OrderController extends VegaController
{
    public function checkoutPage(ViewCartCommand $viewCartCommand)
    {
        $data = $viewCartCommand->execute();

        return $this->render($this->getModuleName() . '::checkout', $data);
    }

    public function store(CreateOrderRequest $request, CreateOrderCommand $createOrderCommand)
    {
        $order = $createOrderCommand->execute($request->validated());

        return redirect(route('shop.order.success', $order->id));
    }

    public function successPage(OrderModel $order)
    {
        return $this->render($this->getModuleName() . '::success', ['order' => $order]);
    }

    public function ordersPage(ViewOrdersCommand $viewOrdersCommand)
    {
        $data = $viewOrdersCommand->execute();

        return $this->render($this->getModuleName() . '::orders', $data);
    }
}
