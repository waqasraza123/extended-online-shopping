<?php

namespace App\Observers;

use App\Http\Controllers\UserController;
use App\Mail\EmailVerification;
use App\User;
use App\UserVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        $token = (new UserController())->token($user);
        //send the mail
        Mail::to($user->email_phone)->send(new EmailVerification($token));
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