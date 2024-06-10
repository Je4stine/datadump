<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['inc_type','amount','slug','label','doc_type'];

    public function exchangeSave(Request $request, Subject $subject){
        foreach($this->fillable as $field){
            if($request->$field){
                $subject->$field = $request->$field;
            }
        }
        if($subject->id){
            $subject->update();
        }else{
            $subject->save();
        }
        return $subject->id;
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function profile(){
        return $this->hasMany(Profile::class);
    }
}
