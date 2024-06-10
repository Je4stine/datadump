<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forget extends Model
{
    //
    protected $fillable = ['email','expiry','token'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
