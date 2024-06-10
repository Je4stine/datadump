<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BidMapper extends Model
{
    //
    protected $fillable = ['amount','deadline','order_id'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
