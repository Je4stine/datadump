<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevisionMessages extends Model
{
    //
    protected $table = 'revisions_messages';
    protected $fillable = ['message','files'];
    public function assign(){
        return $this->belongsTo(Assign::class);
    }
}
