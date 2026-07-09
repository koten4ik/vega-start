<?php


namespace Modules\User\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\ZSupport\App\Helpers\Str;

class UserNameRule implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (Str::checkBlackList($value) === false)
			$fail('Это имя запрещено');


		$max = 45;
		if (mb_strlen($value) > $max) {
			$fail('Допустимо не более '.$max.' символов!');
		}
	}
}
