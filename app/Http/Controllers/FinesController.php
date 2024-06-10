<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fine;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FinesController extends Controller
{
    //
    public function remove(Fine $fine){
        $assign = $fine->assign;
        $fine->delete();
        return redirect("order/$assign->order_id/room/$assign->id")->with('notice',['class'=>'info','message'=>'Fine removed']);
    }
    public function update(Request $request){
        $fine = Fine::findOrFail($request->fine_id);
        $fine->amount = $request->fine_amount;
        $fine->reason = $request->fine_reason;
        $fine->update();
        $assign = $fine->assign;
        return redirect("order/$assign->order_id/room/$assign->id")->with('notice',['class'=>'info','message'=>'Fine removed']);

    }
}
