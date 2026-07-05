<?php

namespace Modules\ShopOrder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\EmailRule;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:32',
            ],
            'email' => [
                'nullable',
                new EmailRule(),
            ],
            'address' => [
                'required',
                'string',
            ],
            'comment' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Укажите имя',
            'phone.required' => 'Укажите телефон',
            'address.required' => 'Укажите адрес доставки',
        ];
    }
}
