<?php

namespace Modules\ShopOrder\Http\Controllers;

use Modules\ShopCart\Commands\ViewCartCommand;
use Modules\ShopOrder\Commands\CreateOrderCommand;
use Modules\ShopOrder\Commands\ViewOrderCommand;
use Modules\ShopOrder\Commands\ViewOrdersCommand;
use Modules\ShopOrder\Http\Requests\CreateOrderRequest;
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

    public function successPage($orderId, ViewOrderCommand $viewOrderCommand)
    {
        return $this->render($this->getModuleName() . '::success', $viewOrderCommand->execute($orderId));
    }

    public function ordersPage(ViewOrdersCommand $viewOrdersCommand)
    {
        $data = $viewOrdersCommand->execute();

        return $this->render($this->getModuleName() . '::orders', $data);
    }
}
