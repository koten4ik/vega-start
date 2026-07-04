<?php

namespace Modules\UserAccountRecovery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class SendRecoveryLinkRequest extends FormRequest
{

    public function rules()
    {
        return [
            'login' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Логин или адрес электронной почты обязательно для заполнения.',
        ];
    }
}
