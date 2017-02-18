<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    protected $fillable = ['id', 'data_sku', 'title', 'brand_id', 'color', 'rating', 'total_ratings', 'old_price',
        'current_price', 'discount', 'local_online', 'shop_id', 'link', 'image', 'stock'];

    public function brand(){
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }

    public function colors(){
        return $this->morphToMany('App\Color', 'color_products');
    }
}
