<?php


namespace Modules\UserAccountRecovery\Commands;


use Illuminate\Support\Str;
use Modules\User\Queries\UserByEmailQuery;
use Modules\User\Queries\UserByLoginQuery;
use Modules\UserAccountRecovery\Models\PasswordResetTokenModel;
use Modules\ZSupport\App\Services\Mailer\Mailer;
use Illuminate\Validation\ValidationException;

class SendRecoveryLinkCommand
{
	public function __construct()
	{
	}

	public function execute($data)
	{
        if (filter_var($data->login, FILTER_VALIDATE_EMAIL)) {
            $user = UserByEmailQuery::get($data->login)
                ->first();
        }else {
            $user = UserByLoginQuery::get($data->login)
                ->first();
        }

        if (
            $user == null
        ) {
            throw ValidationException::withMessages([
                'login' => __('Некорректный логин или почта'),
            ]);
        }

        $token = Str::random(60);

        PasswordResetTokenModel::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

		$recovery_link = route('user.password.changePage', [
			'token' => $token
		]);

		//todo вынести в ивент
		Mailer::sendMail([
			'to' => $user->email,
			'template_id' => 'recovery',
			'vals' => [
				'recovery_link' => $recovery_link,
			]
		]);
	}
}
