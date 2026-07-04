<?php

namespace Modules\UserAccountRecovery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\UserPasswordRule;


class ChangePasswordRequest extends FormRequest
{

    public function rules()
    {
        return [
            'password' => [
                'required',
				new UserPasswordRule()
            ],
            'password_repeat' => [
				'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Укажите пароль',
        ];
    }
}
