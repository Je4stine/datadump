<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
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
}
