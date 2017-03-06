<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Storage extends Model
{
    protected $fillable = ['storage'];
    public $timestamps = false;

    public function mobiles(){
        return $this->belongsToMany('App\Mobile', 'mobile_storage', 'storage_id', 'mobile_id');
    }
}
