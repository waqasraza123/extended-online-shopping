<?php

namespace App\Http\Controllers;

use App\Mobile;
use App\ProductData;
use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Brand;

class APIController extends Controller
{

    public function __construct()
    {
        $this->middleware('verify-api-requests');
    }


    public function homePageData(){

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
            'lg' => collect($lg),
            'brands' => $brands,
            'searchText' => $searchText,
        ]);
        return response()->json($data);
    }



    /**
     * returns the data for searched term
     * @param $searchTerm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function searchData(Request $request, $searchTerm){

        if(!$searchTerm){
            return response()->json([
                'Error' => 'Search Term is Required.'
            ]);
        }else{
            $searchText = $searchTerm;
            //if user specifies the market location
            $marketLocation = $request->input('market_location');
            //if user specifies his/her location
            $userLocation = $request->input('user_location');
            $radius = $request->input('radius');
            $radius = $radius == null ? '0' : $radius;
            $lat = $request->input('lat');
            $long = $request->input('long');
            $userLat = $request->input('user_lat');
            $userLong = $request->input('user_long');
            $offset = $request->input('page');
            $offset = 10*($offset - 1);
            $locationController = new LocationController();

            //get the first mobile and then get the first mobile data
            $mobiles = Mobile::where('title', 'LIKE', '%'.$searchText.'%')
                ->select('title')
                ->groupBy('title')
                ->offset($offset)
                ->limit(20)
                ->get();
            $count = Mobile::where('title', 'LIKE', '%'.$searchText.'%')
                ->select('title')
                ->groupBy('title')
                ->get()
                ->count();
            $data = array();
            $marketLocationMatchedCount = 0;
            foreach ($mobiles as $index => $m){
                $mobile = Mobile::where('title', $m->title)->first();


                //get mobile data
                //contains the shop id as well
                //would return collection
                $mobileData = $mobile->data;
                $price = 999999999999;
                $distance = 999999999999;
                $addMobileSinceWithinRadiusLimit = false;
                $l = null;
                $o = null;
                $available = null;
                $shopLat = null;
                $shopLong = null;
                $marketLocationMatched = false;
                //there would be multiple rows for one iphone 7 say,
                //10 shops having iphone 7 so we need to get the min
                //price only
                foreach ($mobileData as $item){
                    $price = $item->current_price < $price ? $item->current_price : $price;

                    //in other cases
                    if ($lat == null && $item->local_online == 'l'){
                        $l = $item->shop->location;

                        $lat2 = $userLat == null ? $this->isbLat : $userLat;
                        $long2 = $userLong == null ? $this->isbLong : $userLong;
                        $temp = $locationController->getDistance($item->shop->lat, $item->shop->long, $lat2, $long2);
                        if($temp < $distance){
                            $l = $item->shop->location;
                            $distance = $temp;
                            $shopLat = $item->shop->lat;
                            $shopLong = $item->shop->long;
                        }
                    }

                    if ($item->local_online == 'o'){
                        $o = $o == null ? 'online' : $o;
                    }


                    //check if the shop location matched with the user specified location
                    //if user specified the location
                    //echo $lat . ' == ' . $item->shop->lat . ' ; ' . $long . ' == ' . $item->shop->long . '<br>';
                    if ($lat != null && $long != null && $item->local_online == 'l' && (int)$item->shop->lat == (int)$lat && (int)$item->shop->long == (int)$long){
                        $l = $marketLocation;
                        $marketLocationMatched = true;
                        ++$marketLocationMatchedCount;


                        //if user has specified target location then
                        //calculate the distance from that shop
                        $lat2 = $userLat == null ? $this->isbLat : $userLat;
                        $long2 = $userLong == null ? $this->isbLong : $userLong;
                        $distance = $locationController->getDistance($item->shop->lat, $item->shop->long, $lat2, $long2);
                    }
                }

                //if user has specified radius
                //which is not equal to 0
                //if distance is null then
                //mobile is not available on
                //local shops
                if($radius != "0" && isset($distance)){
                    //if shop and user distance
                    //if greater than
                    if(!($distance <= $radius)){
                        $addMobileSinceWithinRadiusLimit = false;
                    }
                    else{
                        $addMobileSinceWithinRadiusLimit = true;
                    }
                }

                if(!empty($l) && !empty($o)){
                    $available = 'both';
                }
                elseif (!empty($o) && $o == 'online')
                    $available = 'online';
                elseif (!empty($l))
                    $available = 'local';

                //defaults to true if radius was
                //not specified or mobile location
                //falls within radius range
                if($radius !== '0' && $addMobileSinceWithinRadiusLimit){
                    $mobile->shop_lat = $shopLat;
                    $mobile->shop_long = $shopLong;
                    $mobile->brand = $mobile->brand->name;
                    $data[$index]['mobile'] = $mobile;
                    $data[$index]['available'] = $available;
                    $data[$index]['location'] = $l;
                    $data[$index]['price'] = $price == 999999999999 ? null : $price;
                    $data[$index]['distance'] = $distance == 999999999999 ? null : $distance;
                }
                //user did not specify the radius
                elseif ($radius == '0'){
                    //user has specified market location
                    //then add only those results
                    //which are matched
                    if($marketLocation != null && $marketLocationMatched){
                        $mobile->shop_lat = $shopLat;
                        $mobile->shop_long = $shopLong;
                        $mobile->brand = $mobile->brand->name;
                        $data[$index]['mobile'] = $mobile;
                        $data[$index]['available'] = $available;
                        $data[$index]['location'] = $l;
                        $data[$index]['price'] = $price == 999999999999 ? null : $price;
                        $data[$index]['distance'] = $distance == 999999999999 ? null : $distance;
                    }
                    elseif ($marketLocation == null){
                        $mobile->shop_lat = $shopLat;
                        $mobile->shop_long = $shopLong;
                        $mobile->brand = $mobile->brand->name;
                        $data[$index]['mobile'] = $mobile;
                        $data[$index]['available'] = $available;
                        $data[$index]['location'] = $l;
                        $data[$index]['price'] = $price == 999999999999 ? null : $price;
                        $data[$index]['distance'] = $distance == 999999999999 ? null : $distance;
                    }
                }
            }
            //Get current page form url e.g. &page=6
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            //Create a new Laravel collection from the array data
            $collection = new Collection($data);

            //Define how many items we want to be visible in each page
            $perPage = 20;

            //Slice the collection to get the items to display in current page
            $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

            //Create our paginator and pass it to the view
            $paginatedSearchResults = new LengthAwarePaginator($data, $count, $perPage);

            $searchController = new SearchController();
            $searchController->storeLatLong($userLat, $userLong);
            $count = count($data);
            array_push($data, [
                'userLat' => $userLat,
                'userLong' => $userLong,
                'marketLat' => $lat,
                'marketLong' => $long,
                'marketLocation' => $marketLocation,
                'userLocation' => $userLocation,
                'marketLocationMatchedCount' => $marketLocationMatchedCount,
                'resultsCount' => $count,
                'radius' => (int)$radius
            ]);
            return $data;
        }
    }




    /**
     * returns single phone data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnSinglePhoneData($id){

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
        $dataHolder = [];
        $finalArr = [];
        foreach ($data as $d){
            $dataHolder['shop_id'] = $d->shop_id;
            $dataHolder['mobile_id'] = $d->mobile_id;
            $dataHolder['link'] = $d->link;
            $dataHolder['old_price'] = $d->old_price;
            $dataHolder['new_price'] = $d->current_price;
            $dataHolder['local_online'] = $d->local_online;
            $dataHolder['title'] = $mobile->title;
            $dataHolder['image_url'] = $mobile->image;
            $dataHolder['brand_id'] = $mobile->brand_id;
            $dataHolder['shop_name'] = Shop::where('id', $d->shop_id)->first()->shop_name;

            array_push($finalArr, $dataHolder);
        }

        return response()->json([$finalArr, $userLat, $userLong]);
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnShopData($id){
        $shop = Shop::find($id);
        return response()->json($shop);
    }



    /**
     * @param $url
     * @return mixed
     */
    public function setImageUrl($url){

        $fullData = '';
        $temp = explode('/', $url);
        foreach ($temp as $index => $t){
            if(count($temp)-2 == $index){
                $t = ucwords($t);
                if(count($temp)-1 != $index){
                    $t = '/' . $t . '/';
                }
            }
            $fullData = $fullData . $t;
        }

        $url = str_replace('localhost:8000', '//clabiane.net/eos/public/', $fullData);
        return $url;
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
            $mobile->brand = $mobile->brand->name;
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
