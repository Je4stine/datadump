<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentSms extends Model
{
    //
    protected $fillable = ['user_id','message','sent','phone'];
}
