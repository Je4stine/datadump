<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paypaltxn extends Model
{
    //
    protected $fillable = ['amount','txn_id','state','create_time','currency','usd_rate','via'];
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
