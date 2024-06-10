<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
    //
    protected $fillable = ['label','description'];

    public function rates(){
        return $this->hasMany(Rate::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function writers(){
        return $this->hasMany(Profile::class);
    }
}
