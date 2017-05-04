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
    protected $redirectTo = '/register/shop';



    /**
     *
     * LoginController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->rememberToken = $request->input('rememberme') == 'on' ? true : false;
        $this->middleware('guest', ['except' => 'logout']);
        $this->middleware('verified', ['only' => 'login']);
        $this->middleware('has-shop', ['only' => 'login']);
    }


    /**
     * logout the user
     */
    public function logout(){
        Auth::logout();

        return redirect('/');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param $email
     * @param $password
     * @return Response
     */
    public function authenticate($email)
    {
        $user = User::where('email_phone', $email)->where('verified', 1)->first();
        Auth::login($user, $this->rememberToken);

        $count = (new ShopController())->getShops($user);

        if($count > 0){
            //redirect the user to select a shop
            return redirect()->route('select-shops-form', ['id' => $user->id]);
        }

        //user has no shops
        else{
            return redirect()->route('register-shop');
        }
    }
}
