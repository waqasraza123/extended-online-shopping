<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
use App\MobileData;
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
        $mobiles = Mobile::paginate(24);
        $searchText = "";
        return view('welcome', compact('mobiles', 'searchText'))->withBrands($brands);
    }

    /**
     * @param $brand
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMobile($brand, $id){
        $mobile = Mobile::find($id);
        $mobileData = MobileData::select('shop_id', 'mobile_id')
            ->where('mobile_id', $id)
            ->groupBy(['shop_id', 'mobile_id'])->get();

        $data = [];
        foreach($mobileData as $m){
            array_push($data, MobileData::where(['mobile_id' => $m->mobile_id, 'shop_id' => $m->shop_id])->first());
        }

        $data = collect($data);
        //return response()->json($data);
        return view('frontend.single', compact('mobile', 'data'));
    }
}
