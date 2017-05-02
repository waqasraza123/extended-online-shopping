<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopCredentialsRequest;
use App\Shop;
use App\User;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @param StoreShopCredentialsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(StoreShopCredentialsRequest $request){

        $data = $request->all();
        $shop = Shop::create([
            'shop_name' => $data['shop_name'],
            'phone' => $data['shop_phone'],
            'market_plaza' => $data['market_plaza'],
            'location' => $data['location'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'user_id' => Auth::user()->id,
            'city' => $data['city'],
        ]);
        return redirect('/home');
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegisterShopForm(){
        return view('auth.shop-register');
    }


    /**
     * accepts user id
     * @param $id
     */
    public function showShopsForm($id){
        $shops = User::find($id)->shops()->pluck('shop_name', 'id');
        return view('auth.shop-selection')->with('shops', $shops);
    }

    /**
     * returns the users shops
     * called in LoginController@authenticate
     * @param User $user
     * @return mixed
     */
    public function getShops(User $user){
        return $user->shops()->count();
    }
}
