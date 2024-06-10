<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //
    protected $fillable = ['template','action','description'];

    public function website(){
        return $this->belongsTo(Website::class);
    }
}
