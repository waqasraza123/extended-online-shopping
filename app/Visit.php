<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['shop_id', 'count', 'date'];
    public $timestamps = false;
}
