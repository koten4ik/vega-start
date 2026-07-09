<?php


namespace Modules\User\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserPasswordRule implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (ctype_digit($value)) {
			$fail('Пароль должен содержать не только цифры');
		}

		if(strlen($value) < 6)
			$fail('Пароль должен быть длинной не менее 6 символов');

		$max = 255;
		if (strlen($value) > $max) {
			$fail('Допустимо не более '.$max.' символов!');
		}
	}
}
