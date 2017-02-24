<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Color;
use App\Laptop;
use App\Mobile;
use App\Shop;
use Illuminate\Http\Request;
use League\Flysystem\Exception;

class PythonController extends Controller
{
    private $shopName, $category, $token, $isMobile, $isLaptop = null;
    /**
     * @param Request $request
     * validate the incoming request
     * and check for the token
     */
    public function validateRequest(Request $request){
        $tokenCompare = 'KH3423K4HPQEQN2342091313K23WDQKDJDDQWJD9804H';
        if(!($this->shopName && $this->category && $this->token && $tokenCompare == $this->token)){
            exit('All Fields Are Required');
        }
    }

    /**
     * @param Request $request
     * @param $shop_name
     * @param $category
     * @param $token
     * @return array
     */
    public function save(Request $request, $shop_name, $category, $token){
        $data = $request->all();
        $this->shopName = $shop_name;
        $this->category = $category;
        $this->token = $token;
        $mobileCategories = ['smartphones', 'mobiles'];
        $laptopCategories = ['laptops', 'laptop'];
        //send the request to be validated
        $this->validateRequest($request);
        foreach($data as $id=>$row)
        {
            if(in_array($category, $mobileCategories)){
                $obj = new Mobile();
                $this->isMobile = true;
            }
            if(in_array($category, $laptopCategories)){
                $obj = new Laptop();
                $this->isLaptop = true;
            }
            //update the data if already there
            $exists = $obj->where('link', $row['url'])->first();
            if($exists){
                $exists->title = $row['title_alt'];
                $exists->brand_id = $this->saveBrand($row['brand']);
                $exists->image = $row['image_url'];
                $exists->link = $row['url'];
                //$exists->rating = $row['rating_percent'];
                $exists->old_price = $row['old_price'];
                $exists->current_price = $row['current_price'];
                $exists->discount = $row['discount_percent'];
                //$exists->total_ratings = $row['total_ratings'];
                $exists->local_online = 'o';
                $exists->stock = $row['stock'];
                $exists->shop_id = $this->getShopId();
                $exists->save();

                $this->saveColors($row['color'], $exists->id);
            }
            else{
                //get the outer keys, data_sku
                $obj->title = $row['title_alt'];
                $obj->brand_id = $this->saveBrand($row['brand']);
                $obj->image = $row['image_url'];
                $obj->link = $row['url'];
                //$exists->rating = $row['rating_percent'];
                $obj->old_price = $row['old_price'];
                $obj->current_price = $row['current_price'];
                $obj->discount = $row['discount_percent'];
                //$exists->total_ratings = $row['total_ratings'];
                $obj->local_online = 'o';
                $obj->stock = $row['stock'];
                $obj->shop_id = $this->getShopId();
                $obj->save();

                $this->saveColors($row['color'], $obj->id);
            }
        }//foreach ends
    }//save ends

    /**
     * save brands and associates with products
     * @param $brandName
     * @return mixed
     */
    public function saveBrand($brandName){
        $brand = Brand::firstOrCreate(['name' => $brandName]);
        return $brand->id;
    }

    /**
     * save the product images
     * @param $imageUrl
     */
    public function saveImage($imageUrl, $title){

        $title = strtolower(str_replace(' ', '_', $title));

        if($this->isMobile){
            $this->createDirectory($this->shopName, 'mobiles');
        }
        else{
            $this->createDirectory($this->shopName, 'laptops');
        }

        try{
            if($this->isMobile){
                copy($imageUrl, public_path().'/uploads/products/mobiles/'.$this->shopName . '/' . $title.'.png');
                return public_path().'/uploads/products/mobiles/'.$this->shopName . '/' . $title.'.png';
            }
            else{
                copy($imageUrl, public_path().'/uploads/products/laptops/'.$this->shopName . '/' . $title.'.png');
                return public_path().'/uploads/products/laptops/'.$this->shopName . '/' . $title.'.png';
            }
        }
        catch (Exception $e){}
    }

    /**
     * sync the colors
     * @param $colors
     */
    public function saveColors($colors, $mobileId){
        $mobile = Mobile::find($mobileId);
        if(strpos($colors, ',') !== false){
            $colors = explode(',', $colors);
        }
        else if (strpos($colors, '|') !== false){
            $colors = explode('|', $colors);
        }

        $colorsArray = array();
        if(is_array($colors) or ($colors instanceof \Traversable))
            foreach ($colors as $color){
                if($color) {
                    $c = Color::firstOrCreate(['color' => $color]);
                    array_push($colorsArray, $c->id);
                }
            }
        $mobile->colors()->sync($colorsArray);
    }

    public function getShopId(){
        return Shop::where('shop_name', ucwords($this->shopName))->first()->id;
    }
}
