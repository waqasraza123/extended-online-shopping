<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ProductData;
use App\Shop;
use App\Storage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Mobile;
use App\Http\Requests\SaveMobileRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use App\Color;
use Illuminate\Database\Eloquent\Collection;

class MobileController extends Controller
{

    /**
     * MobileController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'has-shop']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $paginatedSearchResults = $this->getShopData($request);
        return view('shopkeepers.mobile.index')->with(['mobile' => $paginatedSearchResults]);
    }


    /**
     *
     * @param Request $request
     * @return LengthAwarePaginator
     *
     */
    public function getShopData(Request $request){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $offset = $request->input('page');
        $offset = 10*($offset - 1);
        $products = Auth::user()->shops()->where('shops.id', $shopId)->first()->products()->offset($offset)->limit(10)->get();
        $count = Auth::user()->shops()->where('shops.id', $shopId)->first()->products()->count();

        $data = array();
        foreach ($products as $index => $p){
            $data[$index]['brand'] = Mobile::where('id', $p->mobile_id)->first()->brand->name;
            $data[$index]['current_price'] = $p->current_price;
            $data[$index]['old_price'] = $p->old_price;
            $data[$index]['discount'] = $p->discount;
            $data[$index]['title'] = Mobile::where('id', $p->mobile_id)->first()->title;
            $data[$index]['image'] = Mobile::where('id', $p->mobile_id)->first()->image;
            $data[$index]['mobile_id'] = $p->mobile_id;
            $data[$index]['product_id'] = $p->id;
        }
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($data);

        //Define how many items we want to be visible in each page
        $perPage = 10;

        //Slice the collection to get the items to display in current page
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($data, $count, $perPage);
        $paginatedSearchResults->setPath('/products/mobile');
        return $paginatedSearchResults;
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
     * @param SaveMobileRequest $request
     * @param \App\Http\Controllers\Controller $controller
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveMobileRequest $request, Controller $controller)
    {
        $data = $request->all();
        $destinationPath = '/uploads/products/mobiles'; // upload path
        $fileName = null;
        $controller = new Controller();

        if (Input::file('product_image')) {
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

        $mobileData  = ProductData::create([
            'link' => '#',
            'current_price' => $data['current_price'], //discount price is new price so it would be current price
            'old_price' => $data['old_price'],
            'local_online' => 'l',
            'mobile_id' => $data['title'],
            'shop_id' => $controller->shopId,
            'discount' => $this->discount($data['current_price'], $data['old_price']),
            'stock' => $data['stock']
        ]);
        //filter the array for string color values
        $data['colors'] = array_filter($data['colors'], function($var){return (is_numeric($var));});


        //sync storage
        if($data['storage']){
            $mobileData->storages()->sync($data['storage']);
        }
        $mobileData->colors()->sync($data['colors']);

        if($mobileData){
            return redirect(route('mobile.create'))->with('success', 'Mobile Added!');
        }
        return redirect(route('mobile.create'))->with('error', 'Mobile Could not be added!');
    }



    /**
     * Display the specified resource.
     * accepts mobile id
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $controller = new Controller();

        $mobile = Auth::user()//get current logged in user
            ->shops()       //get his shops
            ->where('shops.id', $controller->shopId) //get current shop
            ->first()
            ->products() //get that shops' products
            ->where('product_data.mobile_id', $id) //get desired product
            ->first();

        return view('shopkeepers.mobile.show', compact('mobile'));
    }



    /**
     * Show the form for editing the specified resource.
     * accepts mobiles id, not product id
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $controller = new Controller();
        $shopId = $controller->shopId;
        $mobile = Mobile::findOrFail($id);
        $colors = $mobile->colors()->pluck("color", "colors.id");
        $storage = $mobile->storages()->pluck('storage', 'storages.id');
        $brands = Brand::pluck('name', 'id');

        return view('shopkeepers.mobile.edit', compact('mobile', 'colors', 'brands', 'storage', 'shopId'));
    }



    /**
     * Update the specified resource in storage.
     * accepts product id, not mobile id
     *
     * @param $id
     * @param SaveMobileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, SaveMobileRequest $request)
    {
        $controller = new Controller();
        $data = $request->all();
        $destinationPath = '/uploads/products/mobiles'; // upload path
        $fileName = null;
        $colorsArray = array();

        if (Input::file('product_image')) {
            $extension = Input::file('product_image')->getClientOriginalExtension(); // getting image extension
            $fileName = str_replace(" ", "_", strtolower($data['title'].' '.$controller->currentShop->shop_name.'.'.$extension)); // renameing image
            Input::file('product_image')->move(public_path().$destinationPath, $fileName);
        }

        if(isset($data['colors']) && !empty($data['colors'])){
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
        }

        ProductData::where('id', $id)->where('shop_id', $controller->shopId)->update([
            'link' => '#',
            'current_price' => $data['current_price'], //discount price is new price so it would be current price
            'old_price' => $data['old_price'],
            'local_online' => 'l',
            'mobile_id' => $data['mobile_id'],
            'shop_id' => $controller->shopId,
            'discount' => $this->discount($data['current_price'], $data['old_price']),
            'stock' => $data['stock']
        ]);

        //filter the array for string color values
        $data['colors'] = array_filter($data['colors'], function($var){return (is_numeric($var));});

        $product = ProductData::where('id', $id)->where('shop_id', $controller->shopId)->first();

        //sync storage
        if($data['storage']){
            $product->storages()->sync($data['storage']);
        }
        $product->colors()->sync($data['colors']);

        if($product){
            return redirect()->back()->with('success', 'Mobile Updated!');
        }

        return redirect()->back()->with('error', 'Mobile Could not be updated!');
    }



    /**
     * Remove the specified resource from storage.
     * accepts product id, not mobile id
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $controller = new Controller();
        $shopId = $controller->shopId;
        $mobile = ProductData::where('id', $id)->where('shop_id', $shopId)->first();

        $mobile->colors()->detach();
        $mobile->storages()->detach();

        $mobile->delete();

        return redirect('products/mobile')->withSuccess('Mobile Deleted!');
    }


    /**
     * @param $newPrice
     * @param $oldPrice
     * @return string
     */
    public function discount($newPrice, $oldPrice){

        if($newPrice == '0'){
            return 0 . '%';
        }
        if($oldPrice == 0){
            return 0 . '%';
        }
        $percent = ($oldPrice - $newPrice)/$oldPrice;
        return number_format( $percent * 100, 2 ) . '%'; // change 2 to # of decimals
    }



    /**
     * return the mobile titles
     * @param Request $request
     * @return mixed
     */
    public function getTitles(Request $request){
        $brandId = $request->input('brand-id');
        $titles = Brand::find($brandId)->mobiles()->pluck('title', 'id');
        return response()->json($titles);
    }



    /**
     * return the colors for mobile
     * @param Request $request
     * @return mixed
     */
    public function getColors(Request $request){
        $mobileId = $request->input('mobile-id');

        $colors = Mobile::find($mobileId)->colors()->pluck('color', 'colors.id');
        return response()->json($colors);
    }



    /**
     * get the storages for mobile
     * @param Request $request
     * @return mixed
     */
    public function getStorages(Request $request){
        $mobileId = $request->input('mobile-id');

        $storages = Mobile::find($mobileId)->storages()->pluck('storage', 'storages.id');
        return response()->json($storages);
    }



    /**
     * get data for bulk add
     * @param Request $request
     * @return mixed
     */
    public function getBulkData(Request $request){

        $brandId = $request->input('brand-id');

        //i need mobiles with color ids and storage ids
        $mobiles = Brand::find($brandId)->mobiles;

        /**
         * need to build the array where on one index,
         * there will be mobile, its colors and storages
         */
        $data = array();

        /*$ids = array();
        foreach ($mobiles as $index => $m){
            array_push($ids, $m->id);
        }
        $colorProducts = DB::table('color_products')
            ->where('color_products_type', 'App\Mobile')
            ->whereIn('color_products_id', $ids)
            ->get();

        $colorProductsIds = array();
        foreach ($colorProducts as $colorProduct){
            array_push($colorProductsIds, $colorProduct->color_id);
        }

        $middleStorages = DB::table('mobile_storage')->whereIn('mobile_id', $ids)->get();
        $middleStoragesIds = array();
        foreach($middleStorages as $middleStorage){
            array_push($middleStoragesIds, $middleStorage->storage_id);
        }*/

        //combine all data sets into one array
        //$colors = Color::whereIn('id', $colorProductsIds)->get();
        //$storages = Storage::whereIn('id', $middleStoragesIds)->get();

        foreach ($mobiles as $index => $m){
            $data[$index]['id'] = $m->id;
            $data[$index]['title'] = $m->title;
            /*$data[$index]['colors'] = $colors
                ->whereIn('id', $colorProducts->where('color_products_id', $m->id)->pluck('color_id')->all())
                ->pluck('color', 'id')->all();
            $data[$index]['storages'] = $storages
                ->whereIn('id', $middleStorages->where('mobile_id', $m->id)->pluck('storage_id')->all())
                ->pluck('storage', 'id')->all();*/
        }
        $data = collect($data);
        return $data;
    }



    /**
     * save bulk data on form
     * @param Request $request
     * @param \App\Http\Controllers\Controller $controller
     */
    public function saveBulkData(Request $request, Controller $controller){
        $this->validate($request, [
            'current_price' => 'required'
        ]);
        $shopId = $controller->shopId;
        $currentShop = $controller->currentShop;
        $productDataId = null;

        $data = $request->all();
        $brandId = $data['brands'];
        $localOnline = $data['local_online'];

        //arrays
        $titles = $data['title'];
        $currentPrice = $data['current_price'];
        $stock = $data['stock'];
        $check = $data['checkbox_mobile_add'];
        $mobileIds = $data['mobile_id'];

        $iterator = new \MultipleIterator();
        $iterator->attachIterator (new \ArrayIterator ($mobileIds));
        $iterator->attachIterator (new \ArrayIterator ($titles));
        $iterator->attachIterator (new \ArrayIterator ($currentPrice));
        $iterator->attachIterator (new \ArrayIterator ($stock));

        foreach ($iterator as $item)
        {

            if(in_array($item[0], $check)){
                //get the title
                $title = preg_replace("/(\\s)|(\\.)/", "_", trim($item[1]));
                $colors = $data['colors_'.$title];
                $storages = $data['storage_'.$title];
                $exists = ProductData::where([
                    'mobile_id' => $item[0],
                    'shop_id' => $shopId
                ])->first();
                if($exists){
                    $productDataId = $exists->id;
                    ProductData::where('mobile_id', $item[0])->where('shop_id', $shopId)->update([
                        'mobile_id' => $item[0],
                        'shop_id' => $shopId,
                        'link' => '#',
                        'old_price' => '0',
                        'discount' => '0',
                        'current_price' => $item[2],
                        'stock' => empty($item[3]) ? 0 : $item[3],
                        'local_online' => 'l'
                    ]);
                }else{
                    $productData = new ProductData([
                        'mobile_id' => $item[0],
                        'shop_id' => $shopId,
                        'link' => '#',
                        'old_price' => '0',
                        'discount' => '0',
                        'current_price' => $item[2],
                        'stock' => empty($item[3]) ? 0 : $item[3],
                        'local_online' => 'l'
                    ]);
                    $productData->save();
                    $productDataId = $productData->id;
                }
                $colors = array_filter($colors);
                $storages = array_filter($storages);

                if(!empty($colors)) {
                    ProductData::find($productDataId)->colors()->sync($colors);
                }
                if(!empty($storages)) {
                    ProductData::find($productDataId)->storages()->sync($storages);
                }
            }
        }
    }//end save bulk data on form



    /**
     * save the excel file
     * and loop through data to store
     * in db
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveExcelBulk(Request $request){
        $this->validate($request, [
            'excel-data' => 'required|mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('excel-data');
        $fileName = $file->getClientOriginalName() . '_' . strtolower(preg_replace('/\\s/', '_', $this->currentShop));
        $destinationPath = public_path() . '/uploads/excel/';
        $file->move($destinationPath, $fileName);

        Excel::load($destinationPath.$fileName, function($reader) {

            // Getting all results
            $results = $reader->get()->toArray();
            $this->compareData($results);
        });

        //delete excel file
        return redirect()->back()->with([
            'success' => 'Data Imported Successfully'
        ]);
    }//end save excel bulk



    /**
     * compare titles to categorize
     * @param $excelData
     */
    public function compareData($excelData){
        $dataController = new DataController();
        $c = new Controller();
        $shopName = $c->currentShop->shop_name;


        //sort the online data files
        //based on brand name
        function build_sorter($key) {
            return function ($a, $b) use ($key) {
                return strnatcmp($a[$key], $b[$key]);
            };
        }
        usort($excelData, build_sorter('brand'));

        $colors = $dataController->colors;
        $skipArray = array();
        //dd($onlineLines);
        $dbMobiles = null;
        foreach($excelData as $ed){
            $ed = array_values($ed);
            if($ed[0] == ""){
                continue;
            }

            //get the brand name from every line
            $brandName = strtolower($ed[6]);
            $dbMobiles = Brand::where('name', $brandName)->first();
            if($dbMobiles){
                $dbMobiles = Brand::find($dbMobiles->id)->mobiles()->where('mobiles.title', '<>', '')->get();
            }
            //if brand name not already in array
            //then fetch the data
            //since brand has changed
            /*if(!(DB::table('temp')->where('brand', $brandName)->where('shop', $shopName)->first())){
                $dbMobiles = Brand::where('name', $brandName)->first();
                if($dbMobiles){
                    $dbMobiles = Brand::find($dbMobiles->id)->mobiles()->where('mobiles.title', '<>', '')->get();
                }
                DB::table('temp')->insert(['brand' => $brandName, 'shop' => $shopName]);
            }*/
            //if brand name is in the array already
            //then keep on reading the data
            //loop through database mobiles
            //to compare
            $mobileMatched = null;
            if(count($dbMobiles) > 0) {
                if (!($mobileMatched == true)) {
                    foreach ($dbMobiles as $mobile) {

                        //if a mobile has matched then
                        //skip that line since it wont
                        //match with any other mobile
                        if ($mobileMatched == true) {
                            break;
                        }
                        $gsmTitle = $mobile->title;

                        //echo $brandName . ' = ' . $gsmFileName . '<br>';
                        //replace everything including (content) with "" galaxy (2016) = galaxy
                        $gsmTitle = preg_replace("/\\((.*?)\\)|(:\\))*/", "", $gsmTitle);

                        //replace the / char and content around it with ""
                        $gsmTitle = preg_replace("/[a-zA-Z0-9]+\\/[a-zA-Z0-9]+/", "", $gsmTitle);

                        $yearPass = false;
                        $colorPass = false;
                        $overallPass = false;
                        $exactMatch = false;

                        //dd($colors);
                        $years = $dataController->years;

                        if ($gsmTitle == 'B' || $gsmTitle == 'b') {
                            if (preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)([^[G]])$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $ed[0])) {
                                $overallPass = true;
                            }
                        }

                        if ($gsmTitle == 'Z' || $gsmTitle == 'z') {
                            if (preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)([^[GH]])$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $ed[0])) {
                                $overallPass = true;
                            }
                        }

                        //if exact title or followed by - or _ or :
                        if (preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)$gsmTitle$/i", $ed[0])) {
                            $exactMatch = true;
                        }

                        if (preg_match("/^.*?(?:(\\s|-|_|:|\\|)?)$gsmTitle(?:(\\s)?)(-|_|:|\\|)+/i", $ed[0])) {
                            $overallPass = true;
                        }

                        if (!($exactMatch == true || $overallPass == true)) {
                            //now handle the case where space comes after title
                            foreach ($years as $y) {

                                //there must be a space after title
                                //after space a year, gb, dual sim, 2g or 3g etc
                                if (preg_match("/^.*?$gsmTitle(\\s)+(?:(($y)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $ed[0])) {
                                    $yearPass = true;
                                }
                            }
                        }

                        /*if(!($exactMatch == true || $overallPass == true || $yearPass == true)) {
                            foreach ($colors as $c){
                                $c = preg_replace('/\\W/', '', $c);
                                if(preg_match("/^.*?$gsmTitle(\\s)+(?:(($c)|(3g|4g|lte|2g)|(Dual Sim)|(\\p{N}GB))?)/i", $onlineTitle)){
                                    $colorPass = true;
                                }
                            }
                        }*/

                        if ($yearPass || $overallPass || $exactMatch) {
                            /*echo 'color pass '. $dataController->returnTrueFalse($colorPass) .'<br>'. ' year pass ' . $dataController->returnTrueFalse($yearPass) . '<br>'. ' overall pass ' .
                                $dataController->returnTrueFalse($overallPass).'<br>' . 'exact match ' . $dataController->returnTrueFalse($exactMatch). '<br>';
                            echo $gsmTitle . ' = ' . $ed[0] . '<br>';*/
                            $this->saveComparedData($mobile->id, $ed, $c->currentShop->id);
                            $mobileMatched = true;
                        } else {
                            /*echo 'All of the passes failed';
                            echo $gsmTitle . ' = ' . $ed[0] . '<br>';*/
                        }
                    }
                }
            }//end if db mobiles is null
            else{
                //echo $brandName. '<br>';
            }
        }
    }


    /**
     * @param $mobileId
     * @param $onlineData
     * @param $shopId
     */
    public function saveComparedData($mobileId, $onlineData, $shopId){

        $mobileController = new MobileController();
        $title = $onlineData[0];
        $oldPrice = $onlineData[1];
        $newPrice = $onlineData[2];
        $colors = explode(',', $onlineData[3]);
        $storages = explode(',', $onlineData[4]);
        $stock = $onlineData[5];
        $brand = $onlineData[6];
        //find color and storage ids
        $colorIds = Color::whereIn('color', $colors)->pluck('id')->toArray();
        $storageIds = Storage::whereIn('storage', $storages)->pluck('id')->toArray();

        $mobileData = ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->first();
        if(!$mobileData){
            $product = ProductData::create([
                'shop_id' => $shopId,
                'mobile_id' => $mobileId,
                'link' => '#',
                'old_price' => (string)$oldPrice,
                'current_price' => (string)$newPrice,
                'discount' => $mobileController->discount($newPrice, $oldPrice),
                'local_online' => 'l',
                'stock' => $stock
            ]);

            $product->colors()->sync($colorIds);
            $product->storages()->sync($storageIds);
        }
        else{
            ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->update([
                'shop_id' => $shopId,
                'mobile_id' => $mobileId,
                'link' => '#',
                'old_price' => (string)$oldPrice,
                'current_price' => (string)$newPrice,
                'discount' => $mobileController->discount($newPrice, $oldPrice),
                'local_online' => 'l',
                'stock' => $stock
            ]);

            $product = ProductData::where('mobile_id', $mobileId)->where('shop_id', $shopId)->first();
            $product->colors()->sync($colorIds);
            $product->storages()->sync($storageIds);
        }
    }
}
