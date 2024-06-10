<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //

    protected $fillable = ['inc_type','amount','label'];

    public function order(){
        return $this->hasMany(Order::class);
    }

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
}
