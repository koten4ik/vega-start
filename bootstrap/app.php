<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            Modules\ZSupport\App\Middleware\RegisterRequestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $exception) {
            \Modules\ZSupport\App\Exceptions\Handler::report($exception);
        });
        //управление отображением ошибок
        //$exceptions->render(function (Throwable $exception, Illuminate\Http\Request $request) {
        //	$render = \Modules\ZSupport\App\Exceptions\Handler::render($request,$exception);
        //	if($render !== 'parent') return $render;
        //	return parent::render($request, $exception);
        //});
    })->create();
