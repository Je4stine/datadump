<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTrait extends Model
{
    //
    protected $table = 'traits';
    protected $fillable = ['trait','description'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
