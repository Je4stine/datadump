<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Suspension extends Model
{
    //
    protected $fillable = ['reason'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
