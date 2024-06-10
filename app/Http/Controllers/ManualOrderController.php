<?php

namespace App\Http\Controllers;

use App\Academic;
use App\AdditionalFeature;
use App\Bid;
use App\Currency;
use App\Document;
use App\Language;
use App\ManualBid;
use App\Order;
use App\Rate;
use App\Repositories\EmailRepository;
use App\Repositories\FileSaverRepository;
use App\Repositories\ManualOrders;
use App\Repositories\MenuRepository;
use App\Repositories\WebsiteRepository;
use App\Style;
use App\Subject;
use App\WriterCategory;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ManualOrderController extends Controller
{
    //
    protected $folder = "manual.";
    protected $manual_orders;
    public function __construct(Request $request, ManualOrders $manualOrders)
    {
        $this->manual_orders = $manualOrders;
        $menu_repository = new MenuRepository($request);
    }

    public function index(){
        $active = $this->manual_orders->getActive();
        return view($this->folder.'index',[
            'tab'=>'assigned',
            'orders'=>$active
        ]);
    }
    public function newManuals(){
        $orders = $this->manual_orders->getNew();
        return view($this->folder.'index',[
            'tab'=>'new',
            'orders'=>$orders
        ]);
    }
    public function loadBidForm(Order $order,Request $request){
        $amount = null;
        $message = null;
        $bid = $order->bids()->where('user_id',Auth::user()->id)->first();
        if($bid){
            $amount = $bid->amount;
            $message = $bid->message;
        }
        return view($this->folder.'tabs.bid_form',[
            'order'=>$order,
            'amount'=>$amount,
            'message'=>$message
        ]);
    }

    public function placeBid(Order $order,Request $request){
        $bid = $order->bids()->where('user_id',Auth::user()->id)->first();
        if(!$bid)
            $bid = new Bid();
        $bid->amount = round($request->amount);
        $bid->message = $request->message;
        $bid->user_id = Auth::user()->id;
        $bid->order_id = $order->id;
        $bid->save();
        $mail_repo = new EmailRepository();
        $mail_repo->sendAdminNote(Auth::user()->email." Has placed/updated bid on Primeserve Order#$order->id");
        return redirect("manual/unassigned");
    }

    public function pending(){
        $orders = $this->manual_orders->getPending();
        return view($this->folder.'index',[
            'tab'=>'pending',
            'orders'=>$orders
        ]);
    }

    public function approved(){
        $orders = $this->manual_orders->getApproved();
        return view($this->folder.'index',[
            'tab'=>'approved',
            'orders'=>$orders
        ]);
    }

    public function revision(){
        $orders = $this->manual_orders->getRevision();
        return view($this->folder.'index',[
            'tab'=>'revision',
            'orders'=>$orders
        ]);
    }

    public function payments(){
        if(Auth::user()->role == 'writer'){
            return view($this->folder.'index',[
                'user'=>Auth::user(),
                'tab'=>'user_payments',
                'orders'=>['good']
            ]);
        }
        return view($this->folder.'index',[
            'tab'=>'payments',
            'orders'=>['good']
        ]);
    }

    public function newOrder(){
        return view($this->folder.'new',[
        ]);
    }

    public function createOrder(Request $request){
        $order = new Order();
        $request->topic = $request->topic;
        $order = $order->exchangeArray($request);
        $order->status = 10;
        $order->is_manual=1;
        $order->order_number = $request->order_number;
        $order->user_id = Auth::user()->id;
        $order->save();
        $fileRepo = new FileSaverRepository();
        $fileRepo->uploadOrderFiles($order,$request);
        return redirect("manual/unassigned");
    }

    public function deleteOrder(Order $order){
        if(Auth::user()->role == 'admin'){
            if($order->status == 10){
                $order->assigns()->delete();
                $order->bidMapper()->delete();
                $order->bids()->delete();
                $order->delete();
                return redirect()->back();
            }
        }
    }
}
