<?php

namespace Modules\ZSupport\App\Middleware;

use Modules\User\Services\UserService;
use Modules\ZSupport\App\Helpers\Dbg;
use Modules\ZSupport\App\Services\Logger\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\ZSupport\App\Services\SessionService;
use Modules\ZSupport\App\Services\SessionStatService;
use Symfony\Component\HttpFoundation\Response;

//регистрация - bootstrap/app.php
/*
$middleware->web(append: [
    Modules\ZSupport\App\Middleware\RegisterRequestMiddleware::class,
]);
*/
class RegisterRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		//Log::info('Запрос выполнен:', ['url' => $request->url()]);
		$response = $next($request);
		$url = substr(request()->getRequestUri(), 0, 99);
		$user_agent = substr(request()->userAgent(), 0, 154);
		$allow_insert = true;

		$uuid_is_new = -1;
		$data = [
			'created_at' => now(),
			'response_status' => $response->status(),
			'method'=>$request->method(),
			'uuid' => UserService::uuid($uuid_is_new),
			'ip' => request()->ip(),
			'uuid_count' => $uuid_is_new,
			'response_time' => Dbg::timeFromStart(),
		];

		$data['user_agent'] = $user_agent;
		$data['url'] = $url;

		$content_type = $request->header('Content-Type');
		if($content_type)
			$data['content_type'] = substr($content_type, 0, 20);

		if(Auth::id()) {
			$data['user_id'] = Auth::id();
		}


		$request_data = request()->all();

		//не фиксируем сильно большие массивы
		//todo '/post/store' получать через имя маршрута
		if(str_contains($url,'/post/store')){
			$request_data = [];
		}

		//не пишем пароли
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
			//сюда все что не должно мусорить таблу запросов
		];
		foreach ($exclude_urls as $ex_url) {
			if (str_contains($url,$ex_url)) {
				$allow_insert = false;
			}
		}

		if($allow_insert === true) {
			DB::table('a_requests')->insert([
				$data
			]);
		}

        return $response;
    }
}
