<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopCredentialsRequest;
use App\Shop;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(StoreShopCredentialsRequest $request){

        $data = $request->all();
        $shop = Shop::create([
            'shop_name' => $data['shop_name'],
            'phone' => $data['shop_phone'],
            'market_plaza' => $data['market_plaza'],
            'location' => $data['location'],
            'user_id' => Auth::user()->id,
            'city' => $data['city'],
        ]);
        return redirect('/home');
    }
}
