<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Brand extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'type'];

    public function mobiles(){
        return $this->hasMany('App\Mobile', 'brand_id', 'id');
    }

    public function laptops(){
        return $this->hasMany('App\Laptop', 'brand_id', 'id');
    }
}
