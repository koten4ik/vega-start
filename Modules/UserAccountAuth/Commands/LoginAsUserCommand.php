<?php


namespace Modules\UserAccountAuth\Commands;


use Modules\ZSupport\App\Exceptions\LogicException;
use Illuminate\Support\Facades\Auth;
use Modules\User\Services\UserService;
use Modules\User\Models\UserModel;

class LoginAsUserCommand
{
    public function execute($data)
    {
        $expires = (int) $data->expires;

        if ($expires < time()) {
            throw new LogicException('link out of date');
        }

        $signature = UserService::loginAsSignature($data->id, $expires);
        if (!hash_equals($signature, (string) $data->signature)) {
            throw new LogicException('link out of date 2');
        }

        $user = UserModel::find($data->id);
        if (!$user) {
            throw new LogicException('user not found');
        }

        Auth::logout();
        UserService::login($user);

        return true;
    }
}
