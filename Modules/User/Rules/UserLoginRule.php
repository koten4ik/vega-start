<?php


namespace Modules\User\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Modules\Post\Models\TagModel;
use Modules\ZSupport\App\Helpers\Str;
use Modules\ZSupport\Domain\Services\RouteService;
use Modules\User\Models\UserModel;

class UserLoginRule implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$allowedChars = 'a-zA-Z0-9._-';
		$pattern = '/[^' . $allowedChars . ']/';
		if (preg_match($pattern, $value))
		{
			$fail('Разрешены только латинские символы и цифры');
		}

		$exist = UserModel::query()
			->where('id', '!=', Auth::id())
			//->where('status',UserModel::STATUS_ACTIVE)
			->where('login',$value)
			->first();
		if($exist){
			$fail('Такой Логин уже существует');
		}


		if(Str::checkBlackList($value) === false)
			$fail('Этот логин запрещен');



		$max = 255;
		if (strlen($value) > $max) {
			$fail('Допустимо не более '.$max.' символов!');
		}
	}
}
