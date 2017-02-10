<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobile extends Model
{
    protected $fillable = ['id', 'data_sku', 'title', 'brand', 'color', 'old_price',
    'current_price', 'discount', 'local_online', 'shop_id', 'link', 'image', 'stock'];
}
