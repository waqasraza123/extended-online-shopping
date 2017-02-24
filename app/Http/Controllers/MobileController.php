<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Mobile;
use App\Http\Requests\SaveMobileRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Session;
use App\Color;

class MobileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('has-shop');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $mobile = Mobile::paginate(24);

        return view('shopkeepers.mobile.index', compact('mobile'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $colors = Color::pluck("color", "id");
        $brands = Brand::pluck('name', 'id');
        $storage = Storage::pluck('storage', 'id');
        return view('shopkeepers.mobile.create', compact('colors', 'brands', 'storage'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SaveMobileRequest $request, Controller $controller)
    {
        $data = $request->all();
        $destinationPath = '/uploads/products/mobiles'; // upload path
        $fileName = null;
        $colorsArray = array();

        if (Input::file('product_image')->isValid()) {
            $extension = Input::file('product_image')->getClientOriginalExtension(); // getting image extension
            $fileName = str_replace(" ", "_", strtolower($data['title'].' '.$controller->currentShop->shop_name.'.'.$extension)); // renameing image
            Input::file('product_image')->move(public_path().$destinationPath, $fileName);
        }

        foreach ($data['colors'] as $key => $c){
            if(!is_numeric($c)){
                $c = ucwords($c);
                $color = Color::firstOrNew(["color" => $c]);
                //create new record
                if($color){
                    $color->color = $c;
                }
                $color->save();
                array_push($data['colors'], $color->id);
            }
        }
        //dd($data['colors']);
        $mobile = null;
        DB::transaction(function () use ($destinationPath, $fileName, $data, $colorsArray) {
            $mobile  = Mobile::create([
                'title' => ucwords($data['title']),
                'brand_id' => $data['brands'],
                'image' => url('/').$destinationPath.'/'.$fileName,
                'link' => '#',
                'current_price' => $data['discount_price'], //discount price is new price so it would be current price
                'old_price' => $data['current_price'],
                'local_online' => 'l',
                'stock' => $data['stock'],
                'shop_id' => $this->shopId == null ? 0 : $this->shopId,
                'discount' => $this->discount($data['discount_price'], $data['current_price'])
            ]);
            //filter the array for string color values
            $data['colors'] = array_filter($data['colors'], function($var){return (is_numeric($var));});

            //save the colors as well
            foreach ($data['colors'] as $key => $color){
                $colorsArray[$color] = ['color_products_type' => 'App\Mobile', 'color_products_id' => $mobile->id];
            }

            //sync storage
            if($data['storage']){
                $mobile->storages()->sync($data['storage']);
            }
            $mobile->colors()->sync($colorsArray);
        }, 5);

        Session::flash('success', 'Mobile added!');
        $response = array(
            'success' => 'Mobile added!'
        );

        if($mobile){
            return redirect(route('mobile.create'))->with('success', 'Mobile Added!');
        }

        return redirect(route('mobile.create'))->with('error', 'Mobile Could not be added!');
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
        $colors = Color::pluck("color", "id");
        $storage = Storage::pluck('storage', 'id');
        $mobile = Mobile::findOrFail($id);
        $brands = Brand::pluck('name', 'id');
        return view('shopkeepers.mobile.edit', compact('mobile', 'colors', 'brands', 'storage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, SaveMobileRequest $request, Controller $controller)
    {

        $data = $request->all();
        $destinationPath = '/uploads/products/mobiles'; // upload path
        $fileName = null;
        $colorsArray = array();

        if (Input::file('product_image')->isValid()) {
            $extension = Input::file('product_image')->getClientOriginalExtension(); // getting image extension
            $fileName = str_replace(" ", "_", strtolower($data['title'].' '.$controller->currentShop->shop_name.'.'.$extension));

            Input::file('product_image')->move(public_path().$destinationPath, $fileName); // uploading file to given path
        }

        foreach ($data['colors'] as $key => $c){
            if(!is_numeric($c)){
                $c = ucwords($c);
                $color = Color::firstOrNew(["color" => $c]);
                //create new record
                if($color){
                    $color->color = $c;
                }
                $color->save();
                array_push($data['colors'], $color->id);
            }
        }
        //dd($data['colors']);
        $mobile = null;
        DB::transaction(function () use ($destinationPath, $fileName, $data, $colorsArray, $id) {
            $mobile  = Mobile::find($id)->update([
                'title' => ucwords($data['title']),
                'brand_id' => $data['brands'],
                'image' => url('/').$destinationPath.'/'.$fileName,
                'link' => '#',
                'current_price' => $data['discount_price'], //discount price is new price so it would be current price
                'old_price' => $data['current_price'],
                'local_online' => 'l',
                'stock' => $data['stock'],
                'shop_id' => $this->shopId == null ? 0 : $this->shopId,
                'discount' => $this->discount($data['discount_price'], $data['current_price'])
            ]);
            //filter the array for string color values
            $data['colors'] = array_filter($data['colors'], function($var){return (is_numeric($var));});

            //save the colors as well
            foreach ($data['colors'] as $key => $color){
                $colorsArray[$color] = ['color_products_type' => 'App\Mobile', 'color_products_id' => $id];
            }

            //sync storage
            $m = Mobile::find($id);
            if($data['storage']){
                $m->storages()->sync($data['storage']);
            }
            $m->colors()->sync($colorsArray);
        }, 5);

        Session::flash('success', 'Mobile added!');
        $response = array(
            'success' => 'Mobile Updated!'
        );

        if($mobile){
            return redirect(route('mobile.create'))->with('success', 'Mobile Updated!');
        }

        return redirect(route('mobile.create'))->with('error', 'Mobile Could not be updated!');
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
        $mobile = Mobile::find($id);
        $mobile->brand()->dissociate();
        $mobile->colors()->detach();
        $mobile->storages()->detach();
        Mobile::destroy($id);

        return redirect('products/mobile')->withSuccess('Mobile Deleted!');
    }

    /**
     * @param $discountPrice
     * @param $currentPrice
     * @return string
     */
    public function discount($newPrice, $oldPrice){
        $percent = ($oldPrice - $newPrice)/$oldPrice;
        if($newPrice == '0'){
            return 0 . '%';
        }
        return number_format( $percent * 100, 2 ) . '%'; // change 2 to # of decimals
    }
}
