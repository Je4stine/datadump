<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GmailContact extends Model
{
    //
    protected $fillable = ['user_id','name','email'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
