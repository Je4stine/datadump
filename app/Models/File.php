<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['url','user_id','filesize','filename','file_type','path','file_for','allow_client'];

    //set order to file
    public function order(){
        return $this->belongsTo(Order::class);
    }

    //set assign to file
    public function assign(){
        return $this->belongsTo(Assign::class);
    }

    //belongs to a certain guy

    public function user(){
        return $this->belongsTo(User::class);
    }
}
