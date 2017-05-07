<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Session;

class LogoutEventListener
{


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }



    /**
     * Handle the event.
     * flush the session
     * data after a logout
     * @return void
     */
    public function handle()
    {
        Session::flush();
    }
}