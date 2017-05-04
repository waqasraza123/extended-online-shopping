<?php

namespace App\Http\Controllers;

use App\Mobile;
use App\ProductData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{


    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('has-shop');
    }



    /**
     * Show the application dashboard.
     * returns the data fro /home
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginatedSearchResults = $this->getShopData($request);
        return view('shopkeepers.mobile.index')->with(['mobile' => $paginatedSearchResults]);
    }



    /**
     * handles the post request
     * after shop selection form
     *
     * @param Request $request
     * @return $this
     */
    public function handle(Request $request){
        $this->setShopId($request);
        $user = Auth::user();
        $paginatedSearchResults = $this->getShopData($request);
        return view('shopkeepers.mobile.index')->with(['mobile' => $paginatedSearchResults, 'user' => $user]);
    }



    /**
     *
     * @param Request $request
     * @return LengthAwarePaginator
     *
     */
    public function getShopData(Request $request){

        $shopId = $this->getShopId($request);
        $offset = $request->input('page');
        $offset = 10*($offset - 1);
        $products = Auth::user()->shops()->where('shops.id', $shopId)->first()->products()->offset($offset)->limit(10)->get();
        $count = Auth::user()->shops()->where('shops.id', $shopId)->first()->products()->count();

        $data = array();
        foreach ($products as $index => $p){
            $data[$index]['brand'] = Mobile::where('id', $p->mobile_id)->first()->brand->name;
            $data[$index]['current_price'] = $p->current_price;
            $data[$index]['old_price'] = $p->old_price;
            $data[$index]['discount'] = $p->discount;
            $data[$index]['title'] = Mobile::where('id', $p->mobile_id)->first()->title;
            $data[$index]['image'] = Mobile::where('id', $p->mobile_id)->first()->image;
            $data[$index]['mobile_id'] = $p->mobile_id;
            $data[$index]['product_id'] = $p->id;
        }
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($data);

        //Define how many items we want to be visible in each page
        $perPage = 10;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($data, $count, $perPage);

        return $paginatedSearchResults;
    }



    /**
     *
     * @param Request $request
     * @return array|Session|string
     *
     */
    public function getShopId(Request $request){

        //shop id will be there when user
        //logs in directly to dashboard
        //but in case of paginated results
        //need to insert the shop id in query string
        //or in the session
        $shopId = $request->input('shop_id');

        //if there is no shop id in request
        //it will happen with paginated results
        if(!$shopId){
            $shopId = session('shop_id');
        }

        $controller = new Controller();
        $shopId = $shopId == null ? $controller->shopId : $shopId;

        return $shopId;
    }



    /**
     * set shop id
     *
     * @param Request $request
     *
     */
    public function setShopId(Request $request){
        session(['shop_id' => $request->input('shop_id')]);
    }
}
