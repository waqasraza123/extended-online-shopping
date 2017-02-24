<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Mobile;
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
        return view('welcome', compact('mobiles'))->withBrands($brands);
    }
}
