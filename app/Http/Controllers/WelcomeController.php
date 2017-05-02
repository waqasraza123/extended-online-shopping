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
    public function index(Request $request)
    {
        $offset = $request->input('page');
        $offset = 10*($offset - 1);
        $brands = Brand::all();
        $searchText = "";
        $location = null;

        //get the first mobile and then get the first mobile data
        $mobiles = Mobile::select('title')
            ->where([
                ['title', '<>', ''],
                ['title', '<>', ':) Smiley'],
                ['brand_id', '<>', '6']
            ])
            ->groupBy('title')
            ->offset($offset)
            ->limit(48)
            ->get();
        $count = Mobile::count();
        $data = array();
        foreach ($mobiles as $index => $m){
            $mobile = Mobile::where('title', $m->title)->first();


            //get mobile data
            //contains the shop id as well
            //would return collection
            $mobileData = $mobile->data;
            $price = 999999999999;
            $l = null;
            foreach ($mobileData as $item){
                $price = $item->current_price < $price ? $item->current_price : $price;

                if ($item->local_online == 'l'){
                    $l = $item->shop->location;
                }
            }
            //now get the shop location


            $data[$index]['mobile'] = $mobile;
            $data[$index]['data'] = $mobileData;
            $data[$index]['price'] = $price;
            $data[$index]['location'] = $l;
        }

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($data);

        //Define how many items we want to be visible in each page
        $perPage = 48;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($data, $count, $perPage);

        return view('welcome', compact('searchText'))->with(['brands' => $brands, 'mobiles' => $paginatedSearchResults]);
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
