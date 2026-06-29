<?php

namespace Modules\User\Queries;


use Modules\User\Models\UserModel;

class UserByEmailQuery
{
	public static function get($email)
	{
		$query = UserModel::where('email', $email)
			/*->where(function($query){
				$query->where('is_deleted', '!=', 1)
					->orWhereNull('is_deleted');
			})*/;

		return $query;
	}

}
