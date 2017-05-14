<?php

namespace Illuminate\Foundation\Auth;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     * @param StoreUserRequest $request
     */
    public function register(StoreUserRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        //dont let the user login before
        //email verification
        //$this->guard()->login($user);

        /*return $this->registered($request, $user)
            ?: redirect($this->redirectPath());*/
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        return false;
    }
}
