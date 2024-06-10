<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    //
    protected $fillable = ['reason','files','action','status','user_id','assign_id'];

    public function order(){
        return $this->belongsTo(Order::class);
    }
    public function user(){
        return $this->$this->belongsTo(User::class);
    }
}
