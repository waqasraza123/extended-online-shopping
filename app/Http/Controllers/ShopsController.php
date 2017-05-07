<?php

namespace App\Http\Controllers;

use App\Shop;
use App\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mobile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ShopsController extends Controller
{
    /**
     * list all the shops
     * @return mixed
     */
    public function index(){
        $data = Shop::all();

        return view('shops.index')->withData($data);
    }


    /**
     * list all products
     * of the specified shop
     *
     * @param $shopId
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewShop(Request $request, $shopId){

        //track the visits count to
        //current shop
        $this->visitsCount($shopId);


        $offset = $request->input('page');
        $offset = 10*($offset - 1);

        //get the first mobile and then get the first mobile data
        $mobiles = Shop::where('id', $shopId)
            ->first()
            ->products()
            ->offset($offset)
            ->limit(48)
            ->get();
        $count = Shop::where('id', $shopId)
            ->first()
            ->products->count();

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($mobiles);

        //Define how many items we want to be visible in each page
        $perPage = 48;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($mobiles, $count, $perPage);

        return view('shops.single')->with(['mobiles' => $paginatedSearchResults]);
    }


    /**
     * returns single shop info
     *
     * @param Request $request
     * @return mixed
     */
    public function getShopInfo(Request $request){
        $shopId = $request->input('shop_id');

        return Shop::find($shopId);
    }



    /**
     * counts the shop visits
     * and save the records to db
     *
     * @param $shopId
     */
    public function visitsCount($shopId){

        //save the count in visits table
        $visit = Visit::where('shop_id', $shopId)->where('date', Carbon::now()->toDateString())->first();
        if($visit){
            $visit->count += 1;
            $visit->save();
        }
        else{
            Visit::create([
                'shop_id' => $shopId,
                'count' => 1,
                'date' => Carbon::now()->toDateString()
            ]);
        }
    }
}
