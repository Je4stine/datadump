<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    //
    protected $fillable = ['assurance'];

    public function website(){
        return $this->belongsTo(Website::class);
    }
}
