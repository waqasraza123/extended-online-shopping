<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopSettingsFormRequest;
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
        $this->middleware(['has-shop'], ['only' => ['showShopSettings', 'updateShopSettings']]);
    }


    /**
     * @param StoreShopCredentialsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(StoreShopCredentialsRequest $request){
        $data = $request->all();

        //create the shop against
        //current logged in user
        //after performing Validation
        //in StoreShopCredentialsRequest Class
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



    /**
     * shows settings
     * for current shop
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showShopSettings(){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $shop = Shop::find($shopId);

        return view('user.shop-settings', compact('shop'));
    }



    /**
     * update the shop settings
     *
     * @param ShopSettingsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShopSettings(ShopSettingsFormRequest $request){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $data = $request->all();
        $shop = Shop::find($shopId);
        $shop->shop_name = $data['shop_name'];
        $shop->phone = $data['phone'];
        $shop->location = $data['location'];
        $shop->lat = $data['lat'];
        $shop->long = $data['long'];
        $shop->market_plaza = $data['market_plaza'];
        $shop->city = $data['city'];
        $shop->user_id = Auth::user()->id;

        $shop->save();

        return redirect()->back()->with('success', 'Shop Settings Updated');
    }
}
