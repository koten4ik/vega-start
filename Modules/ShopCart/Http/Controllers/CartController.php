<?php

namespace Modules\ShopCart\Http\Controllers;

use Modules\ShopCart\Commands\AddToCartCommand;
use Modules\ShopCart\Commands\RemoveFromCartCommand;
use Modules\ShopCart\Commands\UpdateCartItemCommand;
use Modules\ShopCart\Commands\ViewCartCommand;
use Modules\ShopCart\Http\Requests\AddToCartRequest;
use Modules\ShopCart\Http\Requests\RemoveFromCartRequest;
use Modules\ShopCart\Http\Requests\UpdateCartItemRequest;
use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSupport\App\Services\MetaTags;

class CartController extends VegaController
{
    public function cartPage(ViewCartCommand $viewCartCommand)
    {
        MetaTags::addFromPageModule('cart');
        $data = $viewCartCommand->execute();

        return $this->render($this->getModuleName() . '::cart', $data);
    }

    public function add(AddToCartRequest $request, AddToCartCommand $addToCartCommand)
    {
        if ($addToCartCommand->execute($request->validated()))
            return redirect()->back();
    }

    public function update(UpdateCartItemRequest $request, UpdateCartItemCommand $updateCartItemCommand)
    {
        if ($updateCartItemCommand->execute($request->validated()))
            return redirect()->back();
    }

    public function remove(RemoveFromCartRequest $request, RemoveFromCartCommand $removeFromCartCommand)
    {
        if ($removeFromCartCommand->execute($request->validated()))
            return redirect()->back();
    }
}
