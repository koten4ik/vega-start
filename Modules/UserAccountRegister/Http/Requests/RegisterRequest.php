<?php

namespace Modules\UserAccountRegister\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\User\Models\UserModel;
use Modules\User\Rules\UserEmailRule;
use Modules\User\Rules\UserLoginRule;
use Modules\User\Rules\UserPasswordRule;
use Modules\ZSupport\App\Rules\CaptchaRule;


class RegisterRequest extends FormRequest
{

    public function rules()
    {
        return [

            'login' => [
                'required',
                new UserLoginRule()
            ],
            'email' => [
                'required',
                new UserEmailRule()
            ],
            'password' => [
                'required',
                new UserPasswordRule()
            ],
            'password_repeat' => [
                'required',
                'same:password'
            ],


            'profile_gender' => [
                'required',
            ],
            'profile_last_name' => [
                'required',
            ],
            'profile_first_name' => [
                'required',
            ],
            'profile_middle_name' => [
                'nullable',
            ],
            'country_id' => [
                'required',
            ],
            'profile_phone' => ['required', 'digits:11', 'integer'],

            'sponsor_login' => [
                'required_without:ref',
                'nullable',
                'string',
                function ($attribute, $value, $fail) {

                    $sponsor = UserModel::where('login', $value)
                        ->orWhere('email', $value)
                        ->first();

                    if (!$sponsor) {
                        $fail('Спонсор не найден.');
                        return;
                    }

                    $hasRelation = $sponsor->binary()->exists();

                    if (!$hasRelation) {
                        $fail('У спонсора не заполнена структура. Регистрация сейчас не возможна.');
                    }
                },
            ],

            'profile_conditions1' => [
                'required',
            ],
            'profile_conditions2' => [
                'required',
            ],
            'profile_birthday.year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'profile_birthday.month' => ['required', 'integer', 'between:1,12'],
            'profile_birthday.day' => ['required', 'integer', 'between:1,31'],
        ];
    }

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            'profile_phone' => preg_replace('/[^0-9]/', '', $this->profile_phone)
        ]);
    }

    public function messages()
    {
        return [

            'profile_conditions1.required' => 'Примите соглашение',
            'profile_conditions2.required' => 'Примите соглашение',

            'sponsor_login.required_without' => 'Укажите логин вашего спонсора',


            'profile_gender.required' => 'Укажите пол',
            'profile_last_name.required' => 'Укажите Фамилию',
            'profile_first_name.required' => 'Укажите Имя',
            'profile_middle_name.required' => 'Укажите Отчество',
            'country_id.required' => 'Укажите страну',
            'profile_phone.required' => 'Укажите номер телефона',

            'login.required' => 'Укажите логин',

            'max' => 'Допустимо не более :max символов!',

            'email.required' => 'Укажите Email',
            'email.email' => 'Некорректный Email',
            'email.unique' => 'Такой Email уже существует',

            'password.required' => 'Укажите пароль',
            'password_repeat.required' => 'Повторите пароль',
            'password_repeat.same' => 'Пароли не совпадают',

            'profile_birthday.year.required' => 'Выберите год рождения.',
            'profile_birthday.year.integer' => 'Некорректный год рождения.',
            'profile_birthday.year.min' => 'Некорректный год рождения.',
            'profile_birthday.year.max' => 'Некорректный год рождения.',

            'profile_birthday.month.required' => 'Выберите месяц рождения.',
            'profile_birthday.month.integer' => 'Некорректный месяц рождения.',
            'profile_birthday.month.between' => 'Некорректный месяц рождения.',

            'profile_birthday.day.required' => 'Выберите день рождения.',
            'profile_birthday.day.integer' => 'Некорректный день рождения.',
            'profile_birthday.day.between' => 'Некорректный день рождения.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            $year = data_get($this->profile_birthday, 'year');
            $month = data_get($this->profile_birthday, 'month');
            $day = data_get($this->profile_birthday, 'day');

            if (!$year || !$month || !$day) {
                return;
            }

            if (!checkdate((int)$month, (int)$day, (int)$year)) {
                $validator->errors()->add(
                    'profile_birthday',
                    'Укажите корректную дату рождения.'
                );

                return;
            }

            $birthday = Carbon::createFromDate($year, $month, $day);

            if ($birthday->isFuture()) {
                $validator->errors()->add(
                    'profile_birthday',
                    'Дата рождения не может быть в будущем.'
                );
            }

        });
    }
}
