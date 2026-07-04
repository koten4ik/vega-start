<?php


namespace Modules\UserAccountRecovery\Commands;


use Illuminate\Support\Facades\Hash;
use Modules\User\Models\UserModel;
use Modules\UserAccountRecovery\Models\PasswordResetTokenModel;
use Illuminate\Validation\ValidationException;

class ChangePasswordCommand
{
	public function __construct()
	{
	}

	public function execute($request)
	{
		if ($request->password != $request->password_repeat)
			throw ValidationException::withMessages([
				'password_repeat' => __('Пароли не совпадают'),
			]);


        $resetRecord = PasswordResetTokenModel::query()
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord || now()->subHour(2) > $resetRecord->created_at) {
            throw ValidationException::withMessages([
                'password_repeat' => __('Не корректная или устаревшая ссылка'),
            ]);
        }

        $user = UserModel::where('email',$resetRecord->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            PasswordResetTokenModel::where('email', $resetRecord->email)->delete();

            return true;
        }

		//UserService::login($user);


		return false;
	}
}
