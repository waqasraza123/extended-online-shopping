<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileStorages extends Model
{
    public $table = 'mobile_storages';
    protected $fillable = ['storage_id', 'product_id'];
}
