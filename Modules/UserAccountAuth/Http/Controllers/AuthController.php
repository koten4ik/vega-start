<?php

namespace Modules\UserAccountAuth\Http\Controllers;

use Modules\ZSupport\App\Controllers\VegaController;
use Illuminate\Http\Request;
use Modules\UserAccountAuth\Http\Requests\LoginRequest;
use Modules\UserAccountAuth\Commands\LoginAsUserCommand;
use Modules\UserAccountAuth\Commands\LoginUserCommand;
use Modules\UserAccountAuth\Commands\LogoutUserCommand;
use Modules\ZSupport\App\Services\MetaTags;


class AuthController extends VegaController
{

    public function loginPage()
    {
        MetaTags::addFromPageModule('login');
        return $this->render($this->getModuleName() . '::login');
    }

	public function login(LoginRequest $request, LoginUserCommand $loginCommand)
	{
		if($loginCommand->execute($request))
            return redirect(route('user.cabinet'));
	}

	public function logout(LogoutUserCommand $logoutCommand)
	{
		$logoutCommand->execute();

        return redirect('/');
	}

	public function loginas(Request $request, LoginAsUserCommand $loginAsCommand)
	{
		if ($loginAsCommand->execute($request))
            return redirect(route('user.cabinet'));
	}
}
