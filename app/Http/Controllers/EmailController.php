<?php

namespace App\Http\Controllers;

use App\Jobs\SupportEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request){
        $subject = $request->input('subject');
        $body = $request->input('body');
        $from = Auth::user()->email_phone;
        $this->dispatch(new SupportEmailJob($subject, $body, $from));

        return redirect()->back()->with('success', 'Email is Sent');
    }
}
