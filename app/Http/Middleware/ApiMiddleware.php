<?php

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->getUser() === env('API_USER') && $request->getPassword() === env("API_KEY")) {
            return $next($request);
        } else {
            return response('Not Authorized', 403);
        }
    }
}
