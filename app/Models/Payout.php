<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Payout extends Model
{
    //
    protected $fillable = ['payer_id','amount','transaction_reference','state','payment_for','method','pay_key'];

    public function writer(){
        return $this->belongsTo(User::class);
    }

    public function payer(){
        return $this->belongsTo(User::class,'payer_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
