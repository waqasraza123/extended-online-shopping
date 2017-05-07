<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\User;
use App\UserVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @param User $user
     * @return mixed
     */
    public function token(User $user){

        //generate the token
        $token = md5(uniqid(rand(), true));

        //make sure token is unique and
        //does not exist in the db already
        while(UserVerification::where('token', $token)->first()){
            $token = md5(uniqid(rand(), true));
        }

        $user = UserVerification::firstOrCreate(
            [
                'email' => $user->email_phone,
                'token' => $token,
                'created_at' => Carbon::now()->toDateTimeString()
            ]
        );

        return $user->token;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request){
        $this->validate($request, [
            'email_token' => 'required'
        ]);

        $token = $request->input('email_token');
        $exists = UserVerification::where('token', $token)->where('email', $request->input('email'))->first();
        $this->makeUserVerified($request);
        $this->sendResponse($exists, $request);
    }

    /**
     * @param $id
     * @return $this
     */
    public function showVerificationForm($id){

        $user = User::find($id);

        //generate the token for the user
        $this->token($user);

        return view('auth.email-verification')->with('email', $user->email_phone);
    }

    /**
     * @param $exists
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($exists, Request $request){
        if($request->ajax()){
            if($exists){
                echo 'success';
            }
            else{
                echo "error";
            }
        }
        else{
            return redirect()->route('dashboard');
        }
    }

    /**
     * make the user verified
     * after token authentication
     * @param Request $request
     */
    public function makeUserVerified(Request $request){

        $user = User::where('email_phone', $request->input('email'))->first();
        $user->verified = 1;
        $user->save();
    }
}
