<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //
    protected $fillable = ['ip_address','country','HTTP_USER_AGENT'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
