<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        //user has shops
        if($controller->hasShops){
            //check if session has shop id
            //else redirect to select a shop
            if(!$controller->shopId){
                return redirect()->route('select-shops-form', ['id' => Auth::user()->id]);
            }
        }
        //user has not registered a shop yet
        else{
            return redirect()->route('register-shop');
        }

        return $next($request);
    }
}
