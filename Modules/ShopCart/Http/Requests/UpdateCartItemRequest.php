<?php

namespace Modules\ShopCart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cart_item_id' => [
                'required',
                'exists:shop_cart_items,id',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages()
    {
        return [
            'cart_item_id.required' => 'Позиция корзины не найдена',
            'cart_item_id.exists' => 'Позиция корзины не найдена',
            'quantity.required' => 'Укажите количество',
        ];
    }
}
