<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\ProductData;
class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        $locationController = new LocationController();
        $controller = new Controller();
        $searchText = "";
        $location = null;
        $latest = $this->getMobilesSeparatedInSections('latest', $locationController);
        $apple = $this->getMobilesSeparatedInSections('apple', $locationController);
        $samsung = $this->getMobilesSeparatedInSections('samsung', $locationController);
        $htc = $this->getMobilesSeparatedInSections('htc', $locationController);
        $lg = $this->getMobilesSeparatedInSections('lg', $locationController);
        $data = array();

        array_push($data, [
            'latest' => collect($latest),
            'apple' => collect($apple),
            'samsung' => collect($samsung),
            'htc' => collect($htc),
            'lg' => collect($lg)
        ]);

        return view('welcome')->with([
            'brands' => $brands, 'searchText' => $searchText,
            'data' => $data
        ]);
    }

    /**
     * @param $brand
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMobile($brand, $id){
        $mobile = Mobile::find($id);
        $mobileData = ProductData::select('shop_id', 'mobile_id')
            ->where('mobile_id', $id)
            ->groupBy(['shop_id', 'mobile_id'])->get();
        $data = [];
        foreach($mobileData as $m){
            array_push($data, ProductData::where(['mobile_id' => $m->mobile_id, 'shop_id' => $m->shop_id])->first());
        }

        $data = collect($data);
        $userLat = session('user_lat');
        $userLong = session('user_long');
        return view('frontend.single', compact('mobile', 'data', 'userLat', 'userLong'));
    }


    /**
     * returns the mobiles for welcome page
     *
     * @param $category
     * @param LocationController $locationController
     *
     * @return array
     */
    public function getMobilesSeparatedInSections($category, LocationController $locationController){

        $controller = new Controller();

        if($category == 'latest'){

            $mobiles = Mobile::join('product_data', 'mobiles.id', '=', 'product_data.mobile_id')
                ->orderBy('mobiles.release_date', 'DESC')
                ->select('mobiles.*')
                ->distinct()
                ->limit(8)
                ->get();
        }

        if($category == 'apple'){
            $mobiles = Brand::where('name', $category)->first()->mobiles()->orderBy('release_date', 'DESC')->limit(8)->get();
        }

        if($category == 'samsung'){
            $mobiles = Brand::where('name', $category)->first()->mobiles()->where('title', 'NOT LIKE', '%Gear%')->orderBy('release_date', 'DESC')->limit(8)->get();
        }

        if($category == 'htc'){
            $mobiles = Brand::where('name', $category)->first()->mobiles()->orderBy('release_date', 'DESC')->limit(8)->get();
        }

        if($category == 'lg'){
            $mobiles = Brand::where('name', $category)->first()->mobiles()->orderBy('release_date', 'DESC')->limit(8)->get();
        }

        $data = array();
        foreach ($mobiles as $index => $mobile){


            //get mobile data
            //contains the shop id as well
            //would return collection
            $mobileData = $mobile->data;
            $price = 999999999999;
            $onlineShopPrice = 999999999999;
            $distance = 999999999999;
            $l = null;
            $o = null;
            $available = null;
            $shopLat = null;
            $shopLong = null;

            //there are multiple items per phone
            //i.e. iphone 5 may have 5 rows in product data table
            //since item can be on different shops
            foreach ($mobileData as $item){

                //get the minimum price and
                //get the location of that specific shop
                $shopPrice = preg_replace("/[^\\d]+/", "", $item->current_price);

                //check if the item is available
                //online or local or both
                if ($item->local_online == 'l'){
                    if($shopPrice < $price) {
                        $price = $shopPrice;
                        $l = $item->shop->location;
                        $distance = $locationController->getDistance($item->shop->lat, $item->shop->long, $controller->generalLat, $controller->generalLong);;
                        $shopLat = $item->shop->lat;
                        $shopLong = $item->shop->long;
                    }
                    //shops have same value then
                    //show that shop which has min
                    //distance from user
                    elseif ($shopPrice == $price){
                        //check if the item is available
                        //online or local or both
                        $temp = $locationController->getDistance($item->shop->lat, $item->shop->long, $controller->generalLat, $controller->generalLong);

                        //if new shop which has same price
                        //has less distance then update the location
                        if($temp < $distance){
                            $price = $shopPrice;
                            $l = $item->shop->location;
                            $distance = $temp;
                            $shopLat = $item->shop->lat;
                            $shopLong = $item->shop->long;
                        }
                    }
                }
                //item is online
                else {
                    $o = 'online';
                    if($shopPrice < $onlineShopPrice) {
                        $onlineShopPrice = $shopPrice;
                    }
                }
            }
            if(!empty($l) && !empty($o)){
                $available = 'both';
            }
            elseif (!empty($o) && $o == 'online')
                $available = 'online';
            elseif (!empty($l))
                $available = 'local';

            $mobile->shop_lat = $shopLat;
            $mobile->shop_long = $shopLong;
            $data[$index]['mobile'] = $mobile;
            $data[$index]['brand'] = $mobile->brand->name;
            $data[$index]['data'] = $mobileData;
            $data[$index]['price'] = $price;
            $data[$index]['online_price'] = $onlineShopPrice == 999999999999 ? null : $onlineShopPrice;
            $data[$index]['available'] = $available;
            $data[$index]['location'] = $l;
            $data[$index]['distance'] = $distance;
        }
        return $data;
    }
}
