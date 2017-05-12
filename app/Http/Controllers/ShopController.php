<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShopCredentialsRequest;
use App\Shop;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * ShopController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
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
        if(!$shop){
            return response()->json([
                'error' => 'Unable To Create Shop.'
            ]);
        }
        else{
            return response()->json([
                'success' => 'Shop Created Successfully'
            ]);
        }
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegisterShopForm(){
        return view('auth.shop-register');
    }



    /**
     * accepts user id
     *
     * @param $id
     * @return $this
     */
    public function showShopsForm($id){
        $shops = User::find($id)->shops()->pluck('shop_name', 'id');
        return view('auth.shop-selection')->with('shops', $shops);
    }


    /**
     * handles the post request
     * after shop selection form
     *
     * @param Request $request
     * @return $this
     */
    public function handle(Request $request){
        $homeController = new HomeController();
        $homeController->setShopId($request);

        return redirect()->action('HomeController@index');
    }


    public function showShopSettings(){

    }
}
