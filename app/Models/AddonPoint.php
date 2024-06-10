<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddonPoint extends Model
{
    //
    protected $fillable = ['points','reason'];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
