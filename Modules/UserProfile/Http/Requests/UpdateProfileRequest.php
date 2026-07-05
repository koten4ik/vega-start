<?php

namespace Modules\UserProfile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\UserEmailRule;
use Modules\User\Rules\UserLoginRule;
use Modules\User\Rules\UserNameRule;

class UpdateProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                new UserNameRule(),
            ],
            'login' => [
                'required',
                new UserLoginRule(),
            ],
            'email' => [
                'required',
                new UserEmailRule(),
            ],
            'profile_phone' => [
                'nullable',
                'digits:11',
            ],
            'avatar' => [
                'nullable',
                'image',
                'max:2048',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'profile_phone' => $this->profile_phone !== null
                ? preg_replace('/\D/', '', $this->profile_phone)
                : null,
        ]);
    }

    public function messages()
    {
        return [
            'name.required' => 'Укажите имя',
            'login.required' => 'Укажите логин',
            'email.required' => 'Укажите Email',
            'profile_phone.digits' => 'Некорректный номер телефона',
            'avatar.image' => 'Аватар должен быть изображением',
            'avatar.max' => 'Максимальный размер файла: 2 Мб',
        ];
    }
}
