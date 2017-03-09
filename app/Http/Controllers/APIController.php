<?php

namespace App\Http\Controllers;

use App\Mobile;
use App\MobileData;
use App\Shop;
use Illuminate\Http\Request;

class APIController extends Controller
{

    /**
     * returns the data for searched term
     * @param $searchTerm
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function searchData($searchTerm){
        if (!$searchTerm){
            return "search term is required";
        }
        $searchText = $searchTerm;

        //get the first mobile and then get the first mobile data
        $mobiles = Mobile::where('title', 'LIKE', '%'.$searchText.'%')
            ->select('title')
            ->groupBy('title')
            ->get();

        $data = array();
        foreach ($mobiles as $index => $m){
            array_push($data, Mobile::where('title', $m->title)->first());
        }
        $data = collect($data);

        return response()->json($data);
    }

    public function returnBrandData(){

    }

    /**
     * returns single phone data
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnSinglePhoneData($id){

        /*$temp = collect([1499, 1457, 1472, 1507, 1523, 1587, 1596, 1629, 1641, 1696, 1720, 1752, 1746, 1762, 1768, 1774]);
        $completeData = array();*/

        /*foreach ($temp as $t){*/
            //$id = $t;

        $mobile = Mobile::find($id);

        $mobileData = MobileData::select('shop_id', 'mobile_id')
            ->where('mobile_id', $id)
            ->groupBy(['shop_id', 'mobile_id'])->get();

        $data = [];
        foreach($mobileData as $m){
            array_push($data, MobileData::where(['mobile_id' => $m->mobile_id, 'shop_id' => $m->shop_id])->first());
        }

        $data = collect($data);
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

            //array_push($completeData, $finalArr);

        //}
        return response()->json($finalArr);
    }

    public function returnShopData($id){
        $shop = Shop::find($id);
        return response()->json($shop);
    }
}
