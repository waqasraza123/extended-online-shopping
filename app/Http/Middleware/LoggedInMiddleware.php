<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LoggedInMiddleware
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
        //if user is already logged in
        //do nothing else redirect
        if(Auth::check()){
            //user is logged in already

        }else{
            $user = User::where('email_phone', $request->input('email_phone'))->where('password', bcrypt($request->input('password')))->first();

            if(!$user){
                return redirect('/login')->with(['error' => 'Credentials do not match our record.']);
            }
        }

        return $next($request);
    }
}
