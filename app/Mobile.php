<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    protected $fillable = ['id', 'data_sku', 'title', 'brand_id', 'color', 'old_price',
    'current_price', 'discount', 'local_online', 'shop_id', 'link', 'image', 'stock'];

    public function brand(){
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }

    public function colors(){
        return $this->morphToMany('App\Color', 'color_products');
    }

    public function storages(){
        return $this->belongsToMany('App\Storage', 'mobile_storage', 'mobile_id', 'storage_id');
    }
}
