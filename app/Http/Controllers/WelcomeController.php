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
        $searchText = "";

        //get the first mobile and then get the first mobile data
        $mobilesData = Mobile::select('title')
            ->where([
                ['title', '<>', ''],
                ['title', '<>', ':) Smiley'],
                ['brand_id', '<>', '6']
            ])
            ->groupBy('title')
            ->paginate(24);

        $data = array();
        //$mobilesData->chunk(10, function($mobiles) use ($data){
            foreach ($mobilesData as $index => $m){
                //dd(MobileData::where('mobile_id', Mobile::where('title', $m->title)->first()->id)->first());
                //if(MobileData::where('mobile_id', Mobile::where('title', $m->title)->first()->id)->first()){
                    array_push($data, Mobile::where('title', $m->title)->first());
                //}
            }
        //});
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
