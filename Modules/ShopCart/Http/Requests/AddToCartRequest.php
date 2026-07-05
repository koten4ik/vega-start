<?php

namespace Modules\ShopCart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Rules\ProductQuantityRule;

class AddToCartRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'exists:shop_products,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                new ProductQuantityRule(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Товар не выбран',
            'product_id.exists' => 'Товар не найден',
            'quantity.required' => 'Укажите количество',
            'quantity.min' => 'Минимальное количество: :min',
        ];
    }
}
