<?php

namespace Modules\ZSupport\App\Helpers;

class Env
{
	public static function isLocal()
	{
		return env('APP_ENV') == 'local';
	}

	public static function isLocalOrStage()
	{
		return env('APP_ENV') != 'production';
	}

	public static function isProd()
	{
		return env('APP_ENV') == 'production';
	}
}


