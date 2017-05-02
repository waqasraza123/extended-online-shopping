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
        $user = User::where('email_phone', $request->input('email_phone'))->first();

        if(!$user){
            return redirect('/login')->with(['error' => 'Credentials do not match our record.']);
        }

        //user is not verified
        if($user->verified == 0){
            return redirect()->action(
                'UserController@showVerificationForm',
                [
                    'id' => $user->id
                ]
            );
        }
        //if the user is verified
        //login the user
        else{
            return (new LoginController())->authenticate($request->input('email_phone'));
        }
        return $next($request);
    }
}
