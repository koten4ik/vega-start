<?php

namespace Modules\ZSupport\App\Services\Logger;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\User\Services\UserService;

class Log
{
	const TYPE_INFO = 1;
	const TYPE_ERROR = 2;
	const TYPE_ERROR_CRITICAL = 3;
	const TYPE_WARNING = 4;

	const TYPE_DELETE = 10;
	const TYPE_UPDATE = 11;

	protected $table = 'a_logs';

    protected $fillable = [
    ];

	//todo ! слать в телегу error и errorCritical(сразу или в кроне??)
	// тесты запускать(в кроне или при пуше?)

	public static function info($message, $params = []){
		self::add($message, $params, self::TYPE_INFO);
	}

	public static function error($message, $params = []){
		self::add($message, $params, self::TYPE_ERROR);
	}

	public static function errorCritical($message, $params = []){
		self::add($message, $params, self::TYPE_ERROR_CRITICAL);
	}

	public static function warning($message, $params = []){
		self::add($message, $params, self::TYPE_WARNING);
	}

	public static function deleted($message, $params = []){
		self::add($message, $params, self::TYPE_DELETE);
	}

	public static function add($message, array $params, $type)
	{
		$url = substr(request()->getRequestUri(), 0, 255);
		$allow_insert = true;

		$data = [
			'type' => $type,
			'message' => $message,
			'url'=> $url,
			'created_at' => now(),
			'uuid' => UserService::uuid(),
			'ip' => request()->ip(),
			'user_agent' => request()->userAgent(),
		];

		if(Auth::id())
			$data['user_id'] = Auth::id();

		if($params && count($params)>0)
			$data['params'] = json_encode($params, JSON_UNESCAPED_UNICODE);



		$request_data = request()->all();
		if(count($request_data)>0) {
			if(isset($request_data['password']) === true){
				$request_data['password'] = '***';
			}
			if(isset($request_data['password_repeat']) === true){
				$request_data['password_repeat'] = '***';
			}
			$data['request_data'] = json_encode($request_data, JSON_UNESCAPED_UNICODE);
		}



		$exclude_urls = [
			'%7Bpreview%7D',//админка это дает
			'apple-touch-icon',
			'.well-known/',
			'build/assets/',
			'/site/servca',
			'/favicons/',
		];
		foreach ($exclude_urls as $ex_url) {
			if (str_contains($url,$ex_url)) {
				$allow_insert = false;
			}
		}

		$exclude_messages = [
			'The resource owner or authorization server denied',//прила такое делает
		];
		foreach ($exclude_messages as $ex_msg) {
			if (str_contains($message,$ex_msg)) {
				$allow_insert = false;
			}
		}

		if (isset($data['params']) && str_contains($data['params'],'SsrException')) {
			$allow_insert = false;
		}



		if($allow_insert === true) {
			DB::table('a_logs')->insert([
				$data
			]);
		}
	}
}
