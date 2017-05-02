<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileColors extends Model
{
    public $table = 'mobile_colors';
    protected $fillable = ['color_id', 'product_id'];
}
