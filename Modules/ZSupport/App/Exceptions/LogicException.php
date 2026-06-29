<?php

namespace Modules\ZSupport\App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogicException extends HttpException
{
	public function __construct($message = "Произошла ошибка логики", $code = 400)
	{
		parent::__construct($code,$message);
	}
}
