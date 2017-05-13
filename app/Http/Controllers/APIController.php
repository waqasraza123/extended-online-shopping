<?php

namespace App\Http\Controllers;

use App\Mobile;
use App\ProductData;
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
        foreach ($data as $d){

            if($d->image){
                $d->image = $this->setImageUrl($d->image);
            }
        }
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

        $mobile = Mobile::find($id);

        $mobileData = ProductData::select('shop_id', 'mobile_id')
            ->where('mobile_id', $id)
            ->groupBy(['shop_id', 'mobile_id'])->get();

        $data = [];
        foreach($mobileData as $m){
            array_push($data, ProductData::where(['mobile_id' => $m->mobile_id, 'shop_id' => $m->shop_id])->first());
        }

        $data = collect($data);
        $dataHolder = [];
        $finalArr = [];
        foreach ($data as $d){

            $mobile->image = $this->setImageUrl($mobile->image);

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
}
