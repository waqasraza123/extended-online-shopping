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
            array_push($data, Mobile::where('title', $m->title)->first());
        }
        $data = collect($data);
        return view('welcome', compact('searchText'))->withMobiles($data);
    }
}
