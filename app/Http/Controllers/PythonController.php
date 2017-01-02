<?php

namespace App\Http\Controllers;

use App\Laptop;
use App\Mobile;
use Illuminate\Http\Request;
class PythonController extends Controller
{
    private $shopName, $category, $token = null;
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
            }
            if(in_array($category, $laptopCategories)){
                $obj = new Laptop();
            }
            //update the data if already there
            $exists = $obj->where('link', $row['url'])->first();
            if($exists){
                $exists->color = $row['color'];
                $exists->title = $row['title_alt'];
                $exists->brand = $row['brand'];
                $exists->image = $row['image_url'];
                $exists->link = $row['url'];
                $exists->rating = $row['rating_percent'];
                $exists->old_price = $row['old_price'];
                $exists->current_price = $row['current_price'];
                $exists->discount = $row['discount_percent'];
                $exists->total_ratings = $row['total_ratings'];
                $exists->local_online = 'o';
                $exists->shop_id = 1;
                $exists->save();
            }
            else{
                //get the outer keys, data_sku
                $obj->color = $row['color'];
                $obj->title = $row['title_alt'];
                $obj->brand = $row['brand'];
                $obj->image = $row['image_url'];
                $obj->link = $row['url'];
                $obj->rating = $row['rating_percent'];
                $obj->old_price = $row['old_price'];
                $obj->current_price = $row['current_price'];
                $obj->discount = $row['discount_percent'];
                $obj->total_ratings = $row['total_ratings'];
                $obj->local_online = 'o';
                $obj->shop_id = 1;
                $obj->save();
            }
        }//foreach ends
    }//save ends
}
