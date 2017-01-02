<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    protected $fillable = ['id', 'data_sku', 'title', 'brand', 'color', 'rating', 'total_ratings', 'old_price',
        'current_price', 'discount', 'local_online', 'shop_id', 'link', 'image'];
}
