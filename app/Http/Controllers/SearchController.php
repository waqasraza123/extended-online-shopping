<?php

namespace App\Http\Controllers;

use App\Mobile;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request){
        $this->validate($request, [
            'search_text' => 'required'
        ]);
        $searchText = $request->input('search_text');

        //get the first mobile and then get the first mobile data
        $mobiles = Mobile::where('title', 'LIKE', '%'.$searchText.'%')
            ->select('title')
            ->groupBy('title')
            ->get();

        $data = array();
        foreach ($mobiles as $index => $m){
            $mobile = Mobile::where('title', $m->title)->first();


            //get mobile data
            //contains the shop id as well
            //would return collection
            $mobileData = $mobile->data;
            $price = 999999999999;
            $distance = 999999999999;

            //there would be multiple rows for one iphone 7 say,
            //10 shops having iphone 7 so we need to get the min
            //price only
            foreach ($mobileData as $item){
                $price = $item->current_price < $price ? $item->current_price : $price;
                $distance = $this->getDistance($item->shop->location);
            }

            $data[$index]['mobile'] = $mobile;
            $data[$index]['data'] = $mobileData;
            $data[$index]['price'] = $price;
            $data[$index]['location'] = $distance;
        }
        $data = collect($data);
        dd($data);
        return view('welcome', compact('searchText'))->withMobiles($data);
    }

    /**
     * @param $shopLocation
     * get the distance between shop and user location
     */
    public function getDistance($shopLocation){
        $ip = $_SERVER['REMOTE_ADDR'];
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        print_r($details);

        function get_coordinates($city, $street, $province)
        {
            $address = urlencode($city.','.$street.','.$province);
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
        $coordinates1 = get_coordinates($details->region);
        $coordinates2 = get_coordinates($shopLocation);
        if ( !$coordinates1 || !$coordinates2 )
        {
            echo 'Bad address.';
        }
        else
        {
            $dist = GetDrivingDistance($coordinates1['lat'], $coordinates2['lat'], $coordinates1['long'], $coordinates2['long']);
            return $dist['distance'];
        }
    }
}
