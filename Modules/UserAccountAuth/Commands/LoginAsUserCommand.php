<?php


namespace Modules\UserAccountAuth\Commands;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\UserModel;
use Modules\User\Services\UserService;
use Modules\ZSupport\App\Services\LoginAsService;

class LoginAsUserCommand
{
    public function execute(Request $request)
    {
        $email = LoginAsService::validateSsoRequest($request);

        $user = UserModel::where('email', $email)->firstOrFail();

        UserService::login($user);

        $request->session()->regenerate();

        return redirect('/');
    }
}
