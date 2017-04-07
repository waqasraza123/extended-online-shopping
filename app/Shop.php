<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Shop extends Model
{
    protected $fillable = ['shop_name', 'phone', 'market_plaza', 'location', 'user_id', 'city'];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function products(){
        return $this->hasMany('App\ProductData', 'shop_id', 'id');
    }
}
