<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class UserVerifiedMiddleware
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

        $user = Auth::user();

        //user is not verified
        if($user->verified == 0){

            //if the user is not verified then
            //logout the user
            Auth::logout();
            return redirect()->action(
                'UserController@showVerificationForm',
                [
                    'id' => $user->id
                ]
            );
        }
        return $next($request);
    }
}
