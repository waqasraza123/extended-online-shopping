<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\StoreUserRequest;
use App\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\ShopController as ShopController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $shopController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        //$this->shopController = new ShopController();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(StoreUserRequest $request)
    {
        $data = $request->all();
        $errors = array();
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email_phone' => $data['email_phone'],
            'user_location' => $data['user_location'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'password' => bcrypt($data['password']),
        ]);

        echo $user->email_phone;
    }
}
