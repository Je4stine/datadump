<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    //
    protected $fillable = ['user_id','amount','message'];

    /**
     * Belongs to an order
     */

    public function order(){
        return $this->belongsTo(Order::class);
    }

    /**
     * Belongs to a writer
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
