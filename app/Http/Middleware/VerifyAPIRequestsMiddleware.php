<?php

namespace App\Http\Middleware;

use Closure;

class VerifyAPIRequestsMiddleware
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
        if($request->route('api_token') != 'aMlENVPuzKwmNwrS7b2xya44jAM2wsa816cH5WR0'){
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return $next($request);
    }
}
