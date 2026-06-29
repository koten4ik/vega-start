<?php

namespace Modules\ZSupport\App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\QueryException;

use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSupport\App\Services\Logger\Log;
use Throwable;



//в bootstrap/app.php
/*$app->withExceptions(function (Illuminate\Foundation\Configuration\Exceptions $exceptions) {
	$exceptions->report(function (Throwable $exception) {
		\Modules\ZSupport\App\Exceptions\Handler::report($exception);
	});
    //управление отображением ошибок
	//$exceptions->render(function (Throwable $exception, Illuminate\Http\Request $request) {
	//	$render = \Modules\ZSupport\App\Exceptions\Handler::render($request,$exception);
	//	if($render !== 'parent') return $render;
	//	return parent::render($request, $exception);
	//});
});*/

class Handler
{

	public static function report(Throwable $exception)
	{
		$type = Log::TYPE_ERROR;

		if ($exception instanceof NotFoundHttpException
			|| $exception instanceof ModelNotFoundException
			|| $exception instanceof LogicException
			|| $exception instanceof ValidationException
			|| $exception instanceof AuthenticationException
			|| $exception instanceof AuthorizationException
		) {
			$type = Log::TYPE_WARNING;
		}

		$exceptionStatusMap = [
			ValidationException::class => 422,
			AuthenticationException::class => 401,
			AuthorizationException::class => 403,
			HttpResponseException::class => fn($e) => $e->getResponse()->getStatusCode(),
			QueryException::class => 500,
			NotFoundHttpException::class => 404,
			ModelNotFoundException::class => 404,
			TokenMismatchException::class => 419,
			//ViteManifestNotFoundException - это ловится как ViewException потому для этого отдельное условие
		];
		$statusCode = 500; // дефолт
		foreach ($exceptionStatusMap as $class => $code) {
			if ($exception instanceof $class) {
				$statusCode = is_callable($code) ? $code($exception) : $code;
				break;
			}
		}
		if (method_exists($exception, 'getStatusCode')) {
			$statusCode = $exception->getStatusCode();
		}
		//в рендере отдельны шаблон для этого
		if (method_exists($exception, 'getMessage') && str_contains($exception->getMessage(), 'Vite manifest not found')) {
			$statusCode = 503;
		}

		//тг бот(/user/telegram-bot/webhook)
		if (method_exists($exception, 'getMessage') && str_contains($exception->getMessage(), 'Error retrieving user info: Bad Request: invalid user_id')) {
			$statusCode = 400;
		}

		Log::add(
			$exception->getMessage(),
			[
				'status' => $statusCode,
				'ex_class' => get_class($exception),
				'file' => $exception->getFile(),
				'line' => $exception->getLine()
			],
			$type
		);
	}

	public static function render($request, Throwable $exception)
	{
		if ($exception instanceof LogicException) {
			$statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

			if ($request->wantsJson()) {
				return response()->json([
					'message' => $exception->getMessage(),
				], $statusCode);
			}

			return response()->view('errors.system', ['message' => $exception->getMessage()], 400);
			//return Inertia::render('Errors/error', ['message' => $exception->getMessage()]);
		}

		if (($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException)
			&& $request->expectsJson() === false
		) {
			$c = new VegaController();
			return $c->render('Errors/404', [])->toResponse($request)->setStatusCode(404);
		}

		if (method_exists($exception, 'getMessage') && str_contains($exception->getMessage(), 'Vite manifest not found')) {
			return response()
				->view('errors.503', [
					'message' => 'Сайт временно обновляется, попробуйте через минуту.'
				], 503);
		}

		return 'parent';
	}
}
