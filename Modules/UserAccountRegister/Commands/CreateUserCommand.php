<?php

namespace Modules\UserAccountRegister\Commands;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\UserModel;

class CreateUserCommand
{
    public function execute($data): UserModel
    {
        $user = new UserModel();
        $user->name = $data['name'];
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->profile_phone = $data['profile_phone'];
        $user->saveOrFail();

        return $user;
    }
}
