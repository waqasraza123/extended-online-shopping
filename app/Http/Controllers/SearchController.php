<?php

namespace App\Http\Controllers;

use App\Mobile;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request){
        $this->validate($request, [
            'search_text' => 'required',
            'radius' => 'min:1|max:50'
        ]);

        $searchText = $request->input('search_text');
        //if user specifies the market location
        $marketLocation = $request->input('market_location');
        //if user specifies his/her location
        $userLocation = $request->input('user_location');
        $radius = $request->input('radius');
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
                $data[$index]['mobile'] = $mobile;
                $data[$index]['data'] = $mobileData;
                $data[$index]['price'] = $price;
                $data[$index]['available'] = $available;
                $data[$index]['location'] = $l;
                $data[$index]['distance'] = $distance;
            }
            //user did not specify the radius
            elseif ($radius == '0'){
                //user has specified market location
                //then add only those results
                //which are matched
                if($marketLocation != null && $marketLocationMatched){
                    $mobile->shop_lat = $shopLat;
                    $mobile->shop_long = $shopLong;
                    $data[$index]['mobile'] = $mobile;
                    $data[$index]['data'] = $mobileData;
                    $data[$index]['price'] = $price;
                    $data[$index]['available'] = $available;
                    $data[$index]['location'] = $l;
                    $data[$index]['distance'] = $distance;
                }
                elseif ($marketLocation == null){
                    $mobile->shop_lat = $shopLat;
                    $mobile->shop_long = $shopLong;
                    $data[$index]['mobile'] = $mobile;
                    $data[$index]['data'] = $mobileData;
                    $data[$index]['price'] = $price;
                    $data[$index]['available'] = $available;
                    $data[$index]['location'] = $l;
                    $data[$index]['distance'] = $distance;
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

        $this->storeLatLong($userLat, $userLong);
        $count = count($data);
        return view('frontend.search-results', compact('searchText'))->withMobiles($paginatedSearchResults)
            ->with([
                'userLat' => $userLat,
                'userLong' => $userLong,
                'marketLat' => $lat,
                'marketLong' => $long,
                'marketLocation' => $marketLocation,
                'userLocation' => $userLocation,
                'marketLocationMatchedCount' => $marketLocationMatchedCount,
                'resultsCount' => $count,
                'radius' => $radius
            ]);
    }



    /**
     * @param $shopLocation
     * @param $userLocation
     * @return mixed
     */
    public function getDistance($shopLocation, $userLocation){

        $coordinates1 = $this->get_coordinates($userLocation);
        $coordinates2 = $this->get_coordinates($shopLocation);
        if ( !$coordinates1 || !$coordinates2 )
        {
            echo 'Bad address.';
        }
        else
        {
            $dist = $this->GetDrivingDistance($coordinates1['lat'], $coordinates2['lat'], $coordinates1['long'], $coordinates2['long']);
            return $dist['distance'];
        }
    }



    /**
     * returns the driving distance from
     * user location to the shop
     * @param $lat1
     * @param $lat2
     * @param $long1
     * @param $long2
     * @return array
     */
    function GetDrivingDistance($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

        return array('distance' => $dist, 'time' => $time);
    }



    /**
     * get the coordinates for
     * calculating the distance
     * @param $city
     * @return array|bool
     */
    function get_coordinates($city)
    {
        $address = urlencode($city);
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Poland";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        $status = $response_a->status;

        if ( $status == 'ZERO_RESULTS' )
        {
            return FALSE;
        }
        else
        {
            $return = array('lat' => $response_a->results[0]->geometry->location->lat, 'long' => $long = $response_a->results[0]->geometry->location->lng);
            return $return;
        }
    }


    /**
     * return mobile titles for search box
     * @param Request $request
     */
    public function liveSearch(Request $request){
        return $request->all();
    }


    /**
     * stores the lat long
     * for user location
     * to be used in map
     * on single phone pages
     *
     * @param $userLat
     * @param $userLong
     */
    public function storeLatLong($userLat, $userLong){

        //if user lat long is null
        //remove the data from session
        if(!($userLat && $userLong)){
            session()->forget('user_lat');
            session()->forget('user_long');
        }
        else{
            session([
                'user_lat' => $userLat,
                'user_long' => $userLong
            ]);
        }
    }
}
