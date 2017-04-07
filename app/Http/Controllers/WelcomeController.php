<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\ProductData;
use Illuminate\Http\Request;

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
        $searchText = "";

        //get the first mobile and then get the first mobile data
        $mobiles = Mobile::select('title')
            ->where([
                ['title', '<>', ''],
                ['title', '<>', ':) Smiley'],
                ['brand_id', '<>', '6']
            ])
            ->groupBy('title')
            ->paginate(24);

        $data = array();
        foreach ($mobiles as $index => $m){
            $mobile = Mobile::where('title', $m->title)->first();


            //get mobile data
            //contains the shop id as well
            //would return collection
            $mobileData = $mobile->data;
            $price = 999999999999;
            foreach ($mobileData as $item){
                $price = $item->current_price < $price ? $item->current_price : $price;
            }
            //now get the shop location


            $data[$index]['mobile'] = $mobile;
            $data[$index]['data'] = $mobileData;
            $data[$index]['price'] = $price;
        }
        $data = collect($data);
        //return response()->json($data);
        return view('welcome', compact('searchText'))->with(['brands' => $brands, 'mobiles' => $data]);
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
        //return response()->json($data);
        return view('frontend.single', compact('mobile', 'data'));
    }
}
