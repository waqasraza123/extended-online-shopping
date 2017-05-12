<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    protected $fillable = ['id', 'title', 'brand_id', 'color', 'storage', 'image', 'model', 'release_date'];
    protected $dates = ['created_at', 'updated_at', 'release_date'];
    public function brand(){
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }

    public function colors(){
        return $this->morphToMany('App\Color', 'color_products');
    }

    public function storages(){
        return $this->belongsToMany('App\Storage', 'mobile_storage', 'mobile_id', 'storage_id');
    }

    public function data(){
        return $this->hasMany('App\ProductData', 'mobile_id', 'id');
    }
}
