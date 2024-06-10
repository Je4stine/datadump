<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    //
    protected $fillable = ['code','percent','status','min_allowed','website_id'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function website(){
        return $this->belongsTo(Website::class);
    }

    public function exchangeArray($request,Promotion $promotion){
        foreach($this->fillable as $field){
            if(isset($request->$field)){
               $promotion->$field = $request->$field;
            }
        }
        return $promotion;
    }
}
