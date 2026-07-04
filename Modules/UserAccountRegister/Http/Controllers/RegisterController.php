<?php

namespace Modules\UserAccountRegister\Http\Controllers;

use Modules\ZSupport\App\Controllers\VegaController;
use Illuminate\Http\Request;
use Modules\UserAccountRegister\Commands\ActivateUserCommand;
use Modules\UserAccountRegister\Commands\RegisterUserCommand;
use Modules\UserAccountRegister\Http\Requests\RegisterRequest;
use Modules\ZSupport\App\Services\MetaTags;

class RegisterController extends VegaController
{

    public function registerPage()
    {
        MetaTags::addFromPageModule('registration');
        return $this->render($this->getModuleName() . '::register');
    }

    public function registerStore(RegisterRequest $request, RegisterUserCommand $registerUserCommand)
    {
		$registerUserCommand->execute($request);
        return redirect(route('user.profile.page'));
    }

}
