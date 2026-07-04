<?php

namespace Modules\UserAccountRecovery\Http\Controllers;

use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSupport\App\Services\MetaTags;
use Modules\ZSupport\App\Services\SessionService;
use Modules\UserAccountRecovery\Commands\ChangePasswordCommand;
use Modules\UserAccountRecovery\Commands\SendRecoveryLinkCommand;
use Modules\UserAccountRecovery\Http\Requests\SendRecoveryLinkRequest;
use Modules\UserAccountRecovery\Http\Requests\ChangePasswordRequest;

class RecoveryController extends VegaController
{

    public function recoveryPage()
    {
        MetaTags::addFromPageModule('password_reset');
        return $this->render($this->getModuleName() . '::recovery');
    }

    public function sendRecoveryLink(SendRecoveryLinkRequest $request, SendRecoveryLinkCommand $sendRecoveryLinkCommand)
    {
		$sendRecoveryLinkCommand->execute($request);

        return redirect(route('user.password.recoveryPage').'?sended');
    }

    public function changePasswordPage()
    {
        return $this->render($this->getModuleName() . '::change_password');
    }

	public function changePassword(ChangePasswordRequest $request, ChangePasswordCommand $changePasswordCommand)
	{
		if($changePasswordCommand->execute($request)){
            return redirect(route('user.password.changePage').'?changed');
        }
	}

}
