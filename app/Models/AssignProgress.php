<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignProgress extends Model
{
    //
    protected $fillable = ['percent'];

    public function assign(){
        return $this->belongsTo(Assign::class);
    }
}
