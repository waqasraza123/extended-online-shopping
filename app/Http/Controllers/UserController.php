<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\UserSettingsFormRequest;
use App\User;
use App\UserVerification;
use Carbon\Carbon;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'has-shop'], ['only' => ['showProfile', 'updateProfile']]);
    }



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



    /**
     * shows the user profile
     * form to update the information
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfile(){
        $user = Auth::user();
        $controller = new Controller();
        $shopId = $controller->shopId;
        $currentShopProductsCount = $user->shops()->where('shops.id', $shopId)->first()->products()->count();
        return view('user.profile', compact('user', 'currentShopProductsCount', 'shopId'));
    }


    /**
     * update the shop settings
     *
     * @param UserSettingsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UserSettingsFormRequest $request){
        $user = Auth::user();
        $data = $request->all();
        //move the image
        if(Input::file('image'))
        {
            $image = Input::file('image');
            $filename  = strtolower(preg_replace("/\\s/", "_", $user->name)) . time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path().'/users/',$filename);
            $user->image = url('/'). '/users/' . $filename;
        }
        $user->name = $data['name'];
        $user->email_phone = $data['user_name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->about = $data['about'];
        if(isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();
        return redirect()->back()->with('success', 'Settings has been Updated.');
    }
}
