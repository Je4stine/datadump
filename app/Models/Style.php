<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    //
    protected $fillable = ['inc_type', 'label', 'amount'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

}
