<?php

namespace App\Http\Controllers;

use App\Assign;
use App\Currency;
use App\Payout;
use App\Paypaltxn;
use App\Repositories\AdaptivePayment;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\Repositories\PaypalRepository;
use App\Tip;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    //
    protected $folder = "payments.";
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(){
        $currencies = Currency::get();
        $order_payments = 0;
        foreach($currencies as $currency){
            $abbrev = $currency->abbrev;
            $sum = Paypaltxn::where('currency','like',$abbrev)->sum('amount')*$currency->usd_rate;
            $order_payments+=$sum;
        }
        $payouts = Payout::sum('amount');
        $recent_payments = Paypaltxn::paginate(5);
        $recent_payouts = Payout::paginate(5);
        return view($this->folder.'index',[
            'payouts'=>$recent_payouts,
            'payments'=>$recent_payments,
            'total_payments'=>$order_payments,
            'total_payouts'=>$payouts
        ]);
    }

    public function orders(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $txns = Paypaltxn::orderBy('id','desc')->where('order_id','=',$key)->paginate(10);
            }else{
                $txns = Paypaltxn::orderBy('id','desc')->where('txn_id','like',"%$key%")->paginate(10);
            }
        }else{
            $txns = Paypaltxn::orderBy('id','desc')->paginate(10);
        }
        return view($this->folder.'order',[
            'txns'=>$txns,
        ]);
    }
    public function payouts(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $payouts = Payout::join('users','users.id','=','payouts.user_id')
                    ->select('payouts.*','users.id as user_id','users.email')
                    ->where('users.id','like',"$key")
                    ->orderBy('id','desc')->paginate(10);
            }elseif(filter_var($key,FILTER_VALIDATE_EMAIL)){
                $payouts = Payout::join('users','users.id','=','payouts.user_id')
                    ->where('users.email','like',"%$key%")
                    ->select('payouts.*','users.id as user_id','users.email')
                    ->orderBy('id','desc')->paginate(10);
            }else{
                $payouts = Payout::join('users','users.id','=','payouts.user_id')
                    ->where('users.name','like',"%$key%")
                    ->select('payouts.*','users.id as user_id','users.email')
                    ->orderBy('id','desc')->paginate(10);
            }
        }else{
            $payouts = Payout::join('users','users.id','=','payouts.user_id')
                ->select('payouts.*','users.id as user_id','users.email')
                ->orderBy('id','desc')->paginate(10);
        }
        return view($this->folder.'payouts',[
            'payouts'=>$payouts
        ]);
    }

    public function tips(Request $request){
        if($request->search){
            $key = $request->search;
                $tips = Tip::join('assigns','assigns.id','=','tips.assign_id')
                    ->where('user_id','like',$key)
                    ->orWhere('order_id','like',$key)
                    ->select('tips.*')
                    ->paginate(10);
        }else{
            $tips = Tip::orderBy('id','desc')->paginate(10);
        }

        return view($this->folder.'tips',[
            'tips'=>$tips
        ]);
    }

    public function processPayout(Request $request){
        if(isset($request->cancel_txn_id)){
            $reason = $request->reason;
            $payout = Payout::findOrFail($request->cancel_txn_id);
            $user = $payout->user;
            $emailer = new EmailRepository();
            $emailer->sendEmailNote($user,'Payment Request Removed',$reason);
            $payout->delete();
            $adaptive = new AdaptivePayment();
            $adaptive->checkApproved();
            return ['reload'=>true];
        }
        $validator = Validator::make($request->all(),[
            'txn_id'=>'required',
            'reference'=>'required'
        ]);
        if($validator->fails()){
            return $validator->errors()->all();
        }
        $payout = Payout::findOrFail($request->txn_id);
        $payout->transaction_reference = $request->reference;
        $payout->state = 'COMPLETED';
        $payout->update();
        $adaptive = new AdaptivePayment();
        $adaptive->checkApproved();
        return ['reload'=>true];
    }

    public function renewPayout(Payout $payout){
        $user = $payout->user;
        $pay_acc= $user->paymentAccounts()->where([
            ['website','like','paypal']
        ])->first();
        $paypal = new AdaptivePayment();
        $paypal->renewPayment($payout,$pay_acc->email);
        return redirect()->back();
    }

    public function executePayment(Request $request){
        $adaptive = new AdaptivePayment();
        $adaptive->checkApproved();
        return redirect("payments/payouts");
    }
}
