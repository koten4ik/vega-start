<?php

namespace Modules\User\Queries;


use Modules\User\Models\UserModel;

class UserByLoginQuery
{
	public static function get($login)
	{
		$query = UserModel::where('login', $login)
			/*->where(function($query){
				$query->where('is_deleted', '!=', 1)
					->orWhereNull('is_deleted');
			})*/;

		return $query;
	}

}
