<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    //
    protected $folder = "settings.currency.";
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(Request $request){

        if($request->search){
            $key = $request->search;
            $currencies = Currency::where([
                ['deleted','=',0],
                ['name','like',"%$key%"]
            ])->paginate(10);
        }else{
            $currencies = Currency::where([
                ['deleted','=',0],
            ])->paginate(10);
        }
       return view($this->folder.'index',[
            'currencies'=>$currencies
        ]);
    }

    public function add(Request $request){
        $currency = Currency::findOrNew($request->id);
            return view($this->folder.'add',[
                  'currency'=>$currency
         ]);
    }

    public function save(Request $request){
        $currency = Currency::findOrNew($request->id);
        $currency->abbrev = $request->abbrev;
        $currency->name = $request->name;
        $currency->usd_rate = $request->usd_rate;
        $currency->save();
        return redirect('currency')->with('notice',['class'=>'success','message'=>'Currency Added successfully']);
    }

    public function delete(Currency $currency){
        $currency->delete();
        return redirect('currency')->with('notice',['class'=>'info','message'=>'Currency deleted']);
    }
}
