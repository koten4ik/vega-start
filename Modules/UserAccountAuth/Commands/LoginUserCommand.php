<?php


namespace Modules\UserAccountAuth\Commands;


use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Queries\UserByEmailQuery;
use Modules\User\Queries\UserByLoginQuery;
use Modules\User\Services\UserService;


class LoginUserCommand
{
    public function __construct()
    {
    }

    public function execute($data)
    {
        if (filter_var($data->login, FILTER_VALIDATE_EMAIL)) {
            $user = UserByEmailQuery::get($data->login)
                ->first();
        } else {
            $user = UserByLoginQuery::get($data->login)
                ->first();
        }

        if (
            $user == null
            //|| $user->status == UserModel::STATUS_NOACTIVE
        ) {
            throw ValidationException::withMessages([
                'login' => __('Некорректный логин или почта'),
            ]);
        }

        if (Hash::check($data->password, $user->password) == false) {
            throw ValidationException::withMessages([
                'password' => __('Неверный пароль'),
            ]);
        }

        return UserService::login($user);
    }
}
