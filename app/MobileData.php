<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileData extends Model
{
    protected $fillable = ['shop_id', 'mobile_id', 'local_online', 'image', 'link', 'current_price', 'old_price', 'discount'];
    public $table = 'mobile_data';
}
