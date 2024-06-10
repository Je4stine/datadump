<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    //
    protected $fillable = ['amount','user_id','order_id','deadline','bonus','fine','status','rating','comments'];

    /**
     * Show assign order
     */
    public function order(){
        return $this->belongsTo(Order::class);
    }

    /**
     * show assigned writer
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Get assigned order messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function files(){
        return $this->hasMany(File::class);
    }
    public function revisionMessages(){
        return $this->hasMany(RevisionMessages::class);
    }
    public function revisionFiles(){
        return $this->hasMany(File::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * fines on an assigned task
     */
    public function fines(){
        return $this->hasMany(Fine::class);
    }

    public function tip(){
        return $this->hasOne(Tip::class);
    }

    public function progress(){
        return $this->hasOne(AssignProgress::class);
    }


}
