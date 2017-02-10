<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Mobile;
use App\Http\Requests\SaveMobileRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Session;

class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mobile = Mobile::paginate(25);

        return view('shopkeepers.mobile.index', compact('mobile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $colors = ["Red", "Black", "Silver"];
        $brands = ["Samsung", "Apple", "QMobile"];
        return view('shopkeepers.mobile.create', compact('colors', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SaveMobileRequest $request)
    {
        $data = $request->all();
        $destinationPath = '/uploads/products/mobiles'; // upload path
        $fileName = null;

        if (Input::file('product_image')->isValid()) {
            $extension = Input::file('product_image')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111,99999).'.'.$extension; // renameing image
            while(File::exists($fileName)){
                $fileName = rand(11111,99999).'.'.$extension; // renameing image
            }
            Input::file('product_image')->move($destinationPath, $fileName); // uploading file to given path
        }

        Mobile::create([
            'title' => $data['title'],
            'brand' => $data['brands'],
            'image' => public_path().$destinationPath.'/'.$fileName,
            'link' => '#',
            'color' => '#',
            'current_price' => $data['current_price'],
            'old_price' => $data['discount_price'],
            'local_online' => 'l',
            'stock' => $data['stock'],
            'shop_id' => 10,
            'discount' => $this->discount($data['discount_price'], $data['current_price'])
        ]);

        Session::flash('success', 'Mobile added!');
        $response = array(
            'success' => 'Mobile added!'
        );
        return redirect(route('mobile.create'))->with('success', 'Mobile Added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $mobile = Mobile::findOrFail($id);

        return view('shopkeepers.mobile.show', compact('mobile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mobile = Mobile::findOrFail($id);

        return view('shopkeepers.mobile.edit', compact('mobile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        
        $mobile = Mobile::findOrFail($id);
        $mobile->update($requestData);

        Session::flash('flash_message', 'Mobile updated!');

        return redirect('products/mobile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Mobile::destroy($id);

        Session::flash('flash_message', 'Mobile deleted!');

        return redirect('products/mobile');
    }

    /**
     * @param $discountPrice
     * @param $currentPrice
     * @return string
     */
    public function discount($discountPrice, $currentPrice){
        $percent = ($currentPrice - $discountPrice)/$currentPrice;
        return number_format( $percent * 100, 2 ) . '%'; // change 2 to # of decimals
    }
}
