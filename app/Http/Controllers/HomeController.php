<?php

namespace App\Http\Controllers;

use App\Mobile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mobile = Mobile::paginate(24);
        return view('shopkeepers.mobile.index', compact('mobile'));
    }
}
