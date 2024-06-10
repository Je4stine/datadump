<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    //
    protected $fillable = ['amount','reason'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Show the assignment that fine belongs to
     */
    public function assign(){
        return $this->belongsTo(Assign::class);
    }
}
