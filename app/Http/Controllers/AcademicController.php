<?php

namespace App\Http\Controllers;

use App\Academic;
use App\Rate;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AcademicController extends Controller
{
    //
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    /**
     * Show home for academic level settings
     */
    public function index(){
        $academics = Academic::where('deleted','=',0)->get();
        return view('settings.academic.index',[
            'academics'=>$academics
        ]);
    }

    /**
     * Show form for adding or editing an academic level
     */

    public function academicForm($id = null){
        $academic = Academic::findOrNew($id);
        return view('settings.academic.new',[
            'academic'=>$academic
        ]);
    }

    /**
     * save academic level to database
     */

    public function saveAcademic(Request $request){
        $academic = Academic::findOrNew($request->id);
        $academic->level = $request->level;
        $academic->description = $request->description;
        $academic->save();
        return redirect('settings/academic')->with('notice',['class'=>'success','message'=>'Academic level Saved']);
    }

    /**
     * Delete an academic level
     */
    public function deleteAcademic(Academic $academic){
        $academic->deleted = 1;
        $academic->update();
        return redirect('settings/academic')->with('notice',['class'=>'success','message'=>'Academic level has been removed']);
    }

    /**
     * @param Academic $academic
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * Save a rate and redirect to rates page
     */
    public function saveRate(Academic $academic,Request $request){
        $academic->rates()->updateOrCreate(['id'=>$request->id],[
            'id'=>$request->id,
            'hours'=> $request->hours,
            'label'=>$request->label,
            'cost'=>$request->cost,
            'chart'=>$request->chart,
            'ppt'=>$request->ppt
        ]);
        return redirect('settings/academic')->with('notice',['class'=>'success','message'=>'Academic rate has been saved']);
    }

    public function deleteRate(Rate $rate){
        $rate->deleted = 1;
        $rate->update();
        return redirect('settings/academic')->with('notice',['class'=>'success','message'=>'Academic rate has been removed']);
    }

    public function adjustAll(Request $request){
        $type = $request->type;
        $amount = $request->amount;
//        dd($type,$amount);

            $rates = Rate::get();
            foreach($rates as $rate){
                $o_cost = $rate->cost;
                if($type == 'percent'){
                   $n_cost = $o_cost*((100+$amount)/100);
                }else{
                    $n_cost = $o_cost+$amount;
                }
                $rate->cost = $n_cost;
                $rate->update();
        }
        if($request->isXmlHttpRequest()){
            return ['reload'=>true];
        }else{
            return redirect("settings/academic")->with('status',['class'=>'info','message'=>'Success']);
        }
    }

}
