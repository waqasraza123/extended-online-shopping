<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $fillable = ['shop_id', 'mobile_id', 'local_online', 'image', 'link', 'current_price', 'old_price', 'discount'];
    public $table = 'product_data';

    public function shop(){
        return $this->belongsTo('App\Shop', 'shop_id');
    }
}
