<?php


namespace Modules\User\Services;



use Illuminate\Support\Facades\Auth;

class UserRoleService
{
	public static function isAdmin($user)
	{
		return $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
	}

}
