<?php

namespace Modules\ZSupport\App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPackMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check()) {
            return redirect()->route('user.auth.login');
        }
        $user = auth()->user();
        if(!$user->pack){
            /*2.29(2)*/
            return redirect()->route('user.tariff');
        }
        return $next($request);
    }
}
