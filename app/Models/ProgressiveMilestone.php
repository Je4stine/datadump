<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgressiveMilestone extends Model
{
    //
    protected $fillable = ['amount','deadline','instructions','pages'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
