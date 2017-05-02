<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class ShopLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * return the shop login form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shopLoginForm(){
        return view('auth.shop-login');
    }

    public function shopLogin(Request $request){
        $this->validate($request, [
            'email_phone' => 'required',
            'password' => 'required'
        ]);

        $data = $request->all();

        $user = User::where('email_phone', $data['email_phone'])->where('password', bcrypt($data['password']))->first();

        if(Auth::attempt(['email_phone' => $data['email_phone'], 'password' => $data['password']], true)){
            $shops = Auth::user()->shops;

            //if more than one shops then show
            // the option to the user to choose the shop
            if(count($shops) > 1 ){
                //login the user
                return response()->json(['shops' => $shops]);
            }
            else{
                return response()->json(['shop' => $shops->first()]);
            }
        }
        else{
            return response()->json(['errors', 'Username or Password is incorrect.']);
        }
    }
}
