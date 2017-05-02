<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorProducts extends Model
{
    public $table = 'color_products';
    protected $hidden = ['created_at', 'updated_at'];
}
