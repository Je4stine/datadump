<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Urgency extends Model
{
    //
    protected $fillable = ['hours','high_school','under_graduate','masters','phd','label'];
    public function exchangeSave(Request $request, Urgency $urgency){
        foreach($this->fillable as $field){
            if($request->$field){
                $urgency->$field = $request->$field;
            }
        }
        if($urgency->id){
            $urgency->update();
        }else{
            $urgency->save();
        }
        return $urgency->id;
    }
}
