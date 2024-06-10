<?php

namespace App\Http\Controllers;

use App\Promotion;
use App\Repositories\MenuRepository;
use App\Repositories\WebsiteRepository;
use App\Website;
use Illuminate\Http\Request;
use App\Order;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    //
    protected $user;
    public function __construct(Request $request,MenuRepository $menuRepository)
    {
        $this->user = Auth::user();
        $menuRepository->check();
    }

    public function index(){
        $promotions = Promotion::orderBy('id','desc')->paginate(10);
        return view('promotion.index',[
            'promotions'=>$promotions
        ]);

    }

    public function add(Request $request){
        if($request->method()=='POST'){
            $promotion = Promotion::findOrNew($request->id);
            $promo = new Promotion();
            $promotion = $promo->exchangeArray($request,$promotion);
            $promotion->save();
            return redirect("websites/view/$promotion->website_id")->with('notice',['class'=>'success','message'=>'Promotion has been saved']);
        }
    }

    public function changeStatus(Promotion $promotion,$status){
        $promotion->status = $status;
        $promotion->update();
        return redirect("websites/view/$promotion->website_id")->with('notice',['class'=>'success','message'=>'Promotion has been saved']);
    }

    public function delete(Promotion $promotion){
        $promotion->delete();
        return redirect("websites/view/$promotion->website_id")->with('notice',['class'=>'success','message'=>'Promotion has been deleted']);
    }

    public function search(Request $request){

        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        $website = Website::find($website_id);
        $promotions = $website->promotions()->where('code','=',$request->code)->get();
//        dd($request->all());
        if(!$request->total) {
            $order = $request->session()->get('order');
            $total = $order->getTotal();
        }else{
            $total = $request->total;
        }
            if(count($promotions)>0){
                $promotion = $promotions[0];
                if($promotion->min_allowed<=$total){
                    $percentage = $promotion->percent;
                    $response = [
                        'status'=>true,
                        'percent'=>$percentage
                    ];
//                    $order->discounted = $percentage;
//                    $order->update();
                }else{
                    $response = [
                        'status'=>false,
                        'error'=>"Failed, Min allowed is $".number_format($promotion->min_allowed,2)
                    ];
                }

            }else{
                $response = [
                    'status'=>false,
                    'error'=>'Invalid promotion code'
                ];
            }

            echo json_encode($response);
    }
}
