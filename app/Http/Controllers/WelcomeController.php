<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\ProductData;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
        $latest = $this->getMobilesSeparatedInSections('latest', $locationController, $controller);
        $apple = $this->getMobilesSeparatedInSections('apple', $locationController, $controller);
        $samsung = $this->getMobilesSeparatedInSections('samsung', $locationController, $controller);
        $htc = $this->getMobilesSeparatedInSections('htc', $locationController, $controller);
        $lg = $this->getMobilesSeparatedInSections('lg', $locationController, $controller);
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
     * @param Controller $controller
     *
     * @return array
     */
    public function getMobilesSeparatedInSections($category, LocationController $locationController, Controller $controller){
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
            $mobiles = Brand::where('name', $category)->first()->mobiles()->orderBy('release_date', 'DESC')->limit(8)->get();
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
                $price = $item->current_price < $price ? $item->current_price : $price;
                //check if the item is available
                //online or local or both
                if ($item->local_online == 'l'){
                    $temp = $locationController->getDistance($item->shop->lat, $item->shop->long, $this->isbLat, $this->isbLong);
                    //echo $item->shop->shop_name . ' ' . $temp . ' < ' . $distance . ' = ' . '<br>';
                    if($temp < $distance){
                        $l = $item->shop->location;
                        $distance = $temp;
                        $shopLat = $item->shop->lat;
                        $shopLong = $item->shop->long;
                    }
                }else {
                    $o = 'online';
                }
            }
            if(!empty($l) && !empty($o)){
                $available = 'both';
            }
            elseif (!empty($o) && $o == 'online')
                $available = 'online';
            elseif (!empty($l))
                $available = 'local';

            /*if($available == null){
                continue;
            }*/
            $mobile->shop_lat = $shopLat;
            $mobile->shop_long = $shopLong;
            $data[$index]['mobile'] = $mobile;
            $data[$index]['data'] = $mobileData;
            $data[$index]['price'] = $price;
            $data[$index]['available'] = $available;
            $data[$index]['location'] = $l;
            $data[$index]['distance'] = $distance;
        }

        return $data;
    }
}
