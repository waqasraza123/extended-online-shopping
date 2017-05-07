<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Shop extends Model
{
    protected $fillable = ['shop_name', 'phone', 'market_plaza', 'location', 'user_id', 'city'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(){
        return $this->hasMany('App\ProductData', 'shop_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visits(){
        return $this->hasMany('App\Visit', 'shop_id');
    }
}
