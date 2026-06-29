<?php


namespace Modules\User\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\UserModel;

class UserEmailRule implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			$fail('Некорректный Email');
		}

		$exist = UserModel::query()
			->where('email',$value)
			->where('id', '!=', Auth::id())
			->first();
		if($exist){
			$fail('Такой Email уже существует');
		}

		$max = 255;
		if (strlen($value) > $max) {
			$fail('Допустимо не более '.$max.' символов!');
		}
	}
}
