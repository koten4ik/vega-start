<?php

namespace Modules\UserProfile\ViewModels;

use Modules\User\Models\UserModel;
use Modules\User\Services\UserService;

class ProfileViewModel
{
    public static function data(UserModel $user): array
    {
        $data = [];

        if (!$user) return false;

        $data = [
            'name' => $user->name,
            'login' => $user->login,
            'email' => $user->email,
            'profile_phone' => $user->profile_phone,
            'avatar' => UserService::getAvatar($user),
        ];

        return $data;
    }
}
