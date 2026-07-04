<?php


namespace Modules\UserAccountAuth\Commands;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class LogoutUserCommand
{
	public function execute()
	{
		Auth::logout();
	}
}
