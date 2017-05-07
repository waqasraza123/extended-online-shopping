<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = ['shop_id', 'product_id', 'revenue'];

    public function shop(){
        return $this->belongsTo('App\Shop');
    }

    public function product(){
        return $this->belongsTo('App\ProductData');
    }
}
