<?php

namespace App\Observers;

use App\Http\Controllers\UserController;
use App\Mail\EmailVerification;
use App\User;
use App\UserVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserVerificationObserver
{
    protected $email = null;
    protected $token = null;

    /**
     * Listen to the User created event.
     *
     * @param  UserVerification $userVerification
     * @return void
     */
    public function created(UserVerification $userVerification)
    {
        $this->token = $userVerification->token;
        $this->email = $userVerification->email;

        //send the mail
        Mail::to($this->email)->send(new EmailVerification($this->token));
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        //
    }
}