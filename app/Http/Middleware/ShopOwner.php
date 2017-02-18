<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;
class ShopOwner
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
        $controller = new Controller();
        if(!$controller->shopId){
            return redirect(route('home'));
        }
        return $next($request);
    }
}
