<?php

namespace App\Http\Controllers;

use App\Brand;
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
        return view('welcome')->withBrands($brands);
    }
}
