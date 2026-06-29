<?php

namespace Modules\ZSupport\App\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;

//регистрация - bootstrap/app.php
// в withMiddleware в $middleware->group('web', [ VerifyCsrfToken::class,
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/user/auth/tg',
        '/user/telegram-bot/webhook',

    ];

	protected function tokensMatch($request)
	{
		// Только проверяем CSRF если веб-пользователь авторизован
		if (Auth::guard('web')->check()) {
			return parent::tokensMatch($request);
		}
		// Публичные POST-запросы без токена → CSRF пропускаем
		return true;
	}
}
