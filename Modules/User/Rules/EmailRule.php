<?php


namespace Modules\User\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if ($value != '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			$fail('Некорректный Email');
		}
	}
}
