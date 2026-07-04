<?php


namespace Modules\UserAccountRegister\Commands;


use Illuminate\Support\Facades\Auth;
use Modules\User\Services\UserService;
use Modules\ZSupport\App\Services\Mailer\Mailer;
use Modules\ZSupport\App\Services\RedSms\RedSmsService;

class RegisterUserCommand
{
	public function __construct(private CreateUserCommand $createUserCommand,private RedSmsService $redSmsService){}

	public function execute($request)
	{

		$user = $this->createUserCommand->execute($request->validated());

        $code = (string) rand(100000, 999999);
        $user->email_verify_code = $code;
        $user->save();

        Mailer::sendMail([
            'to' => $user->email,
            'template_id' => 'email_confirm',
            'vals' => [
                'code' => $code,
            ]
        ]);

        UserService::login($user);

		return true;
	}
}
