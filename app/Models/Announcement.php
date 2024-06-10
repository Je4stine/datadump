<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //
    protected $fillable=['target','message'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
