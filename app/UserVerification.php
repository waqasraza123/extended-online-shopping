<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    public $table = 'users_verifications';
    protected $fillable = ['email', 'token', 'created_at'];
    public $timestamps = false;
}
