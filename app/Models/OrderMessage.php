<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    //
    protected $fillable = ['order_id','user_id','message','client_id','sender'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function client(){
        return $this->belongsTo(User::class,'client_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
