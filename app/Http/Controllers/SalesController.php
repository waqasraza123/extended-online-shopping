<?php

namespace App\Http\Controllers;

use App\ProductData;
use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mobile;

class SalesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'has-shop']);
    }

    public function showSales(Controller $controller){
        $shopId = $controller->shopId;

        return view('sales.index', 'shopId');
    }



    public function showItemsToSell(){
        $data = $this->getListing();

        return view('sales.show-items')->with(['listing' => $data]);
    }


    /**
     * @param Request $request
     * @return $this
     */
    public function fetchProduct(Request $request){
        //get the mobile id
        $mobileId = $request->input('mobile-id');

        $data = $this->getSingleProductData($mobileId);
        $listing = $this->getListing();

        return view('sales.show-items')->with(['listing' => $listing, 'mobile' => $data]);
    }


    /**
     * return the listing for the
     * products of the shop
     *
     * @return array
     */
    public function getListing(){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $products = Auth::user()->shops()->where('shops.id', $shopId)->with('products', 'products.mobile')->get();
        $data = array();
        foreach ($products as $product){
            foreach($product['relations']['products'] as $relation){
                $data[$relation['relations']['mobile']->id] = isset($data[$relation['relations']['mobile']->id]) ? "" : $relation['relations']['mobile']->title;
            }
        }

        return $data;
    }


    /**
     * accepts mobile id
     * @param $id
     * @return array|\Illuminate\Support\Collection
     */
    public function getSingleProductData($id){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $mobile = Mobile::find($id)->data()->where('product_data.shop_id', $shopId)->first();
        return $mobile;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sellProduct(Request $request){
        $this->validate($request, [
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'base_price' => 'required|numeric'
        ]);

        $price = $request->input('price');
        //mobile_id is actually product id
        $productId = $request->input('mobile_id');
        $p = ProductData::where('id', $productId)->first();
        $p->stock  -= $request->input('quantity');
        $p->save();

        if($p){
            $this->recordSales($productId, $price);
        }

        return redirect()->back()->with(['success' => 'Mobile Sold!']);
    }


    /**
     * record the sales for
     * current shop
     *
     * @param $productId
     * @param $price
     */
    public function recordSales($productId, $price){
        $controller = new Controller();
        $shopId = $controller->shopId;

        Sales::create([
            'shop_id' => $shopId,
            'product_id' => $productId,
            'revenue' => $price
        ]);
    }
}
