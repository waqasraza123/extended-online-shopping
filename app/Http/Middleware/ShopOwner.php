<?php

namespace App\Http\Middleware;

use App\User;
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
        $user = User::where('email_phone', $request->input('email_phone'))->first();


        //user has not registered a shop
        if(!$controller->shopId){
            return redirect()->route('register-shop');
        }

        return $next($request);
    }
}
