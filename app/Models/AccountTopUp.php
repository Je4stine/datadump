<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountTopUp extends Model
{
    //
    protected $fillable = ['amount','usd_rate','reference','currency_id','via','redeemed_points'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
