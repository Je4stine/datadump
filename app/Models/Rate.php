<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    //
    protected $fillable = ['hours','cost','label','chart','ppt'];

    public function academic(){
        return $this->belongsTo(Academic::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
