<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ["color"];
    public $timestamps = false;

    /*color is morphed by many mobiles*/
    public function mobiles(){
        return $this->morphedByMany('App\Mobile', 'color_products');
    }

    public function laptops(){
        return $this->morphedByMany('App\Laptop', 'color_products');
    }
}
