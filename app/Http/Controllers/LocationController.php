<?php

namespace App\Http\Controllers;

use App\Shop;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    function getDistance($lat1, $lon1, $lat2, $lon2) {

        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return round($km, 2) + 1.5;
    }


    /**
     * returns the lat long for the
     * maps on single product page
     * required in ajax
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function  getShopLatLong(Request $request){
        $shopId = $request->input('shop_id');

        $shop = Shop::where('id', $shopId)->first();
        $lat = $shop->lat;
        $long = $shop->long;

        return response()->json([
            'lat' => $lat,
            'long' => $long
        ]);
    }
}
