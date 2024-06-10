<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    //
    protected $fillable = ['website','email'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
