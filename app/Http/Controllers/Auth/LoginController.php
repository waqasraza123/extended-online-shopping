<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ShopController;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $rememberToken = false;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     *
     * LoginController constructor.
     *
     */
    public function __construct()
    {
        //guest middleware redirects the
        //users after successful authentication
        //or if the user is already logged in
        $this->middleware('guest')->except('logout');
    }



    /**
     * logout the user
     */
    public function logout(){
        Auth::logout();

        return redirect('/');
    }
}
