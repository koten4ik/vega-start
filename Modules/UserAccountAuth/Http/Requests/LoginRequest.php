<?php

namespace Modules\UserAccountAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ZSupport\App\Rules\CaptchaRule;
use Modules\User\Rules\EmailRule;

class LoginRequest extends FormRequest
{

    public function rules()
    {
        return [
            'login' => [
                'required',
            ],
            'password' => [
                'required',
                'string',
                'min:1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Укажите логин или почту',

			'password.required' => 'Укажите пароль',
            'password.min' => 'Пароль не менее :min символов',
        ];
    }
}
