<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterCategory extends Model
{
    //
   protected $fillable = ['amount','name','inc_type','cpp','deadline'];

    public function writers(){
        return $this->belongsTo(User::class);
    }

}
