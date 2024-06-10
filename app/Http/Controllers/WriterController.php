<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Bid;
use App\BidMapper;
use App\Repositories\EmailRepository;
use App\Repositories\FileSaverRepository;
use App\Repositories\WordRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\MenuRepository;
use App\Http\Requests;
use App\Order;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use App\Assign;
use App\Repositories\AdaptivePayment;
use App\File;
use App\Article;
class WriterController extends Controller
{
    protected $emailer;
    protected $orders;
    protected $user;
    protected $fileSaver;
    protected $active_status = 0;
    protected $available_status = 0;
    protected $revision_status = 2;
    protected $active_bids = 0;
    protected $completed_status = 4;
    protected $pending_status = 3;

    protected $client_active_status=1;
    protected $client_pending_status = 4;
    protected $client_closed_status = 6;
    protected $client_unassigned_status = 0;
    public function __construct(OrderRepository $orders, FileSaverRepository $fileSaverRepository,Request $request)
    {
        $this->fileSaver = $fileSaverRepository;
        $this->middleware('auth');
        $this->orders = $orders;
        $this->user = Auth::user();
        $this->emailer = new EmailRepository();
        new MenuRepository($request);
    }

    public function index(Request $request){
//        $bids = $this->user->bids()->where('status','=',$this->available_status)->select('order_id')->get();
//        $bidded_orders = [];
//        foreach($bids as $bid){
//            $bidded_orders[]=$bid->order_id;
//        }
//        $available = BidMapper::where('status','=',1)->whereNotIn('order_id',$bidded_orders)->count();
//        $revision = $this->user->assigns()->where('status','=',$this->revision_status)->count();
//        $completed = $this->user->assigns()->whereIn('status',[$this->pending_status,$this->completed_status])->count();
//        $active = $this->user->assigns()->where('status','=',$this->active_status)->count();
//        $bidded = $this->user->bids()->where('status','=',$this->active_bids)->count();
//        return View('writer.index',[
//            'active'=>$active,
//            'revision'=>$revision,
//            'completed'=>$completed,
//            'available'=>$available,
//            'bidded'=>$bidded
//        ]);
         $user_id=Auth::user()->id;
                if(Auth::user()->is_author == 1){
            $tab = $request->tab;
            if(!$tab){
                $tab = 'approved';
            }
            if($tab == 'drafts'){
                
        $draft_articles=Article::where('user_id',$user_id)
            ->where('status',0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
                $articles = $draft_articles;
            }
            if($tab == 'approved'){
        $approved_articles=Article::where('user_id',$user_id)
            ->where('status',2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            $articles = $approved_articles;
            }
            if($tab == 'pending'){
        $pending_articles=Article::where('user_id',$user_id)
            ->where('status',1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
                $articles = $pending_articles;
            }
            if($tab == 'edit'){
                $article = Article::find($request->id);
                 return view('client.articles.index',[
                'articles'=>@$articles,
                'tab'=>$tab,
                'article'=>$article
            ]);
            }
            $approved_count = Article::where('user_id',$user_id)
            ->where('status',2)->count();
            $pending_count = Article::where('user_id',$user_id)
            ->where('status',1)->count();
            $drafts_count = Article::where('user_id',$user_id)
            ->where('status',0)->count();
            return view('client.articles.index',[
                'approved_count'=>$approved_count,
                'pending_count'=>$pending_count,
                'drafts_count'=>$drafts_count,
                'articles'=>@$articles,
                'tab'=>$tab
            ]);
        }
        $profile = $this->user->profile;
        $styles = json_decode($profile->style_ids);
        $subjects = json_decode($profile->subject_ids);
        $bids = $this->user->bids()->where('status','=',$this->available_status)->select('order_id')->get();
        $bidded = [];
        foreach($bids as $bid){
            $bidded[]=$bid->order_id;
        }
        $bidmappers =BidMapper::where([
            ['bid_mappers.status','=',1],
            ['bid_mappers.allowed','like',"%".$this->user->writer_category_id."%"]
        ])
            ->join('orders','orders.id','=','bid_mappers.order_id')
//            ->whereIn('orders.style_id',$styles)
//            ->whereIn('orders.subject_id',$subjects)
            ->whereNotIn('bid_mappers.order_id',$bidded)->orderBy('deadline','asc')
            ->select('bid_mappers.*')
            ->paginate(10);
        return View('writer.index',[
            'bidmappers'=>$bidmappers,
            'tab'=>'available'
        ]);
    }

    /**
     * show active orders to writer
     */
    public function active(){
//        $user = $this->user;
        $active =  Order::join('assigns','orders.id','=','assigns.order_id')
            ->where([
            ['assigns.status',$this->active_status],
            ['orders.deleted_at',null],
            ['assigns.user_id','=',$this->user->id],
            ['assigns.is_manual','=',0]
        ])
            ->select('assigns.id as assign_id','assigns.deadline as adeadline','assigns.created_at as acreated_at','orders.*')
            ->orderBy('assigns.id','desc')
            ->paginate(10);
//        dd($active);

        return View('writer.index',[
            'active'=>$active,
            'tab'=>'active'
        ]);
    }

    /**
     *  show orders on revisions
     */
    public function revision(){
        $user = $this->user;
       $revision =  $this->user->assigns()->where([
            ['status',$this->revision_status],
           ['is_manual','=',0]
        ])
            ->orderBy('id','desc')
            ->paginate(15);
        return View('writer.index',[
            'revision'=>$revision,
            'tab'=>'revision'
        ]);
    }

    /**
     * Show pending completed orders
     */
    public function pending(){
        return View('writer.pending',[

        ]);
    }

    /**
     * show completed orders
     */
    public function completed(){
        $assigns =  $this->user->assigns()
            ->join('orders','orders.id','=','assigns.order_id')
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status])
            ->select('assigns.*','orders.status as order_status','orders.topic','orders.pages')
            ->where('assigns.is_manual','=',0)
            ->orderBy('assigns.id','desc')
            ->paginate(10);
        return View('writer.index',[
            'assigns'=>$assigns,
            'tab'=>'completed'
        ]);
    }
    /**
     * Show writer bids
     */
    public function bids(){
        $bids = $this->user->bids()->where('status','=',$this->active_bids)->orderBy('id','desc')->paginate(10);
//        dd($bids);
        return View('writer.index',[
            'bids'=>$bids,
            'tab'=>'bids'
        ]);
    }
    /**
     * Show writer payments
     */
    public function payment(){
        $assigns =  $this->user->assigns()
            ->join('orders','orders.id','=','assigns.order_id')
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status])
            ->select('assigns.*','orders.status as order_status','orders.topic','orders.pages')
            ->orderBy('assigns.id','desc')
            ->paginate(10);
        return View('writer.payment',[
            'assigns'=>$assigns
        ]);
    }

    /**
     * Show available orders to writer
     */
    public function available(){
        $profile = $this->user->profile;
        $styles = json_decode($profile->style_ids);
        $subjects = json_decode($profile->subject_ids);
        $bids = $this->user->bids()->where('status','=',$this->available_status)->select('order_id')->get();
        $bidded = [];
        foreach($bids as $bid){
            $bidded[]=$bid->order_id;
        }
        $bidmappers =BidMapper::where('bid_mappers.status','=',1)
            ->join('orders','orders.id','=','bid_mappers.order_id')
//            ->whereIn('orders.style_id',$styles)
//            ->whereIn('orders.subject_id',$subjects)
            ->whereNotIn('bid_mappers.order_id',$bidded)->orderBy('deadline','asc')
            ->select('bid_mappers.*')
            ->paginate(10);
        return View('writer.available',[
            'bidmappers'=>$bidmappers
        ]);
    }

    /**
     * Show a single order
     */

    public function viewOrder(Order $order){
        $files = $this->orders->getOrderFiles($order->id);
        $assign = $order->assigns()->whereIn('status',[$this->revision_status,$this->active_status])->first();
        if($assign){
//            dd($files);
            return view('writer.room',[
                'order'=>$order,
                'assign'=>$assign,
                'files'=>$files
            ]);
        }
        return View('writer.order',[
            'order'=>$order
        ]);
    }
    /**
     * writer can bid/edit bid here
     */
    public function bid($bidMapperid){
        $bidMapper = BidMapper::findOrFail($bidMapperid);
        $order = $bidMapper->order;
        $mybid = @$order->bids()->where('user_id',$this->user->id)->get()[0];
        return View('writer.bid',[
            'order'=>$order,
            'mybid'=>$mybid,
            'bidmapper'=>$bidMapper
        ]);

    }


    /**
     * place bid on order
     */
    public function addBid(Request $request, $bidMapperid){
        $emailer = new EmailRepository();
            $bidMapper = BidMapper::findOrFail($bidMapperid);
        $order = $bidMapper->order;
        $mybid = @$order->bids()->where('user_id',$this->user->id)->get()[0];
        $amount = number_format($request->amount,2);
        if($mybid){
            $mybid->amount = $amount;
            $mybid->message = $request->message;
            $mybid->save();

            $mail = 'Hello Admin, <br/>
                '.$this->user->name.' Has updated his/her bid on order#<strong>'.$order->id.'</strong>
                Please check
';
            $emailer->sendAdminNote($mail);

        }else{
            $order->bids()->create([
                'user_id'=>$this->user->id,
                'amount'=>$amount,
                'message'=>$request->message
            ]);
            $mail = 'Hello Admin, <br/>
                '.$this->user->name.' Has placed a bid on order#<strong>'.$order->id.'</strong>
                Please check
';
            $emailer->sendAdminNote($mail);


        }
        return redirect("/writer/available")->with('notice',['class'=>'success','message'=>'your bid has been placed. Please wait admin to assign ']);
    }
    /**
     * Chat room for assigned order
     */
    public function orderRoom(Order $order, Assign $assign, Request $request)
    {
       $assign->messages()->where([
           ['user_id','!=',$this->user->id]
       ])->update([
            'seen'=>1
        ]);
        $method = $request->method();
        if($method=='POST'){
            $type = $request->type;
            $paths = $this->fileSaver->saveAssignFiles($request,$assign,$type);
            if($request->type=='Final Copy'){
                $now = Carbon::now();
                $deadline = Carbon::createFromTimestamp(strtotime($assign->deadline));
                if($now>$deadline){
                    $rate = $this->user->writerCategory->fine_percent/100;
                    $fine = number_format($rate*$assign->amount,2);
                    $assign->fines()->create([
                        'amount'=>$fine,
                        'reason'=>'Late Order, Auto 15% fine'
                    ]);
                    $request->session()->flash('notice',['class'=>'success','message'=>"You have been fined $fine"]);
                }
//
                $assign->status = 3;
                $assign->update();
            }

        }
        $files = File::where([
            ['assign_id','=',0],
            ['order_id','=',$order->id]
        ])->orWhere([
            ['assign_id','=',$assign->id]
        ])->orderBy('id','asc')->get();
        return View('writer.room',[
            'order'=>$order,
            'assign'=>$assign,
            'files'=>$files
        ]);
    }
    /**
     * get order counts
     */

    public function getOrderCounts(){
        $return = [];
        if($this->user->role=='writer'){
            $active = $this->user->assigns()->where([
                ['status',$this->active_status],
                ['is_manual',0],
            ])
                ->orderBy('deadline','asc')
                ->count();
            $manual_active = $this->user->assigns()->where([
                ['status',$this->active_status],
                ['is_manual',1],
            ])
                ->orderBy('deadline','asc')
                ->count();
            $available = $this->getAvailableCount();
            $revision = $this->user->assigns()->where([
                ['status',$this->revision_status]
            ])
                ->orderBy('id','desc')
                ->count();
            $bids = $this->user->bids()->where('status','=',$this->active_bids)->orderBy('id','desc')->count();
            $closed = $this->user->assigns()->whereIn('status',[$this->pending_status,$this->completed_status])->orderBy('updated_at','asc')->count();
            $return[] =  ['data_count'=>$available,'target'=>'writer_dashboard'];
            $return[] =  ['data_count'=>$active,'target'=>'writer_active'];
            $return[] = ['data_count'=>$bids,'target'=>'writer_bids'];
            $return[] = ['data_count'=>$revision,'target'=>'writer_revision'];
            $return[] = ['data_count'=>$closed,'target'=>'writer_completed'];
            $return[] = ['data_count'=>$manual_active,'target'=>'manually_assigned'];
        }elseif($this->user->role=='client'){
            $active = $this->user->orders()->where('paid','=',1)->whereIn('status',[$this->client_active_status,$this->client_unassigned_status])->orderBy('deadline', 'asc')->count();
            $unpaid = $this->user->orders()->where([
                ['paid','=',0],
                ['status','!=',8]
            ])->count();
            $disputes = $this->user->disputes()->where('status','=',0)->orderBy('id','asc')->count();
            $completed = $this->user->orders()->whereIn('status',[$this->client_pending_status])->count();
            $approved = $this->user->orders()->whereIn('status',[$this->client_closed_status])->count();

            $archived = $this->user->orders()->whereIn('status',[8])->count();
            $return[] = ['data_count'=>$active,'target'=>'client_active'];
            $return[] = ['data_count'=>$unpaid,'target'=>'client_un_payment'];
            $return[] = ['data_count'=>$completed,'target'=>'client_completed'];
            $return[] = ['data_count'=>$archived,'target'=>'client_archived'];
            $return[] = ['data_count'=>$approved,'target'=>'client_approved'];
            $return[] = ['data_count'=>$disputes,'target'=>'client_disputes'];
        }
        echo json_encode($return);

    }

    function getAvailableCount(){
        $profile = $this->user->profile;
        $styles = json_decode($profile->style_ids);
        $subjects = json_decode($profile->subject_ids);
        $bids = $this->user->bids()->where('status','=',$this->available_status)->select('order_id')->get();
        $bidded = [];
        foreach($bids as $bid){
            $bidded[]=$bid->order_id;
        }
        $bidmappers =BidMapper::where([
            ['bid_mappers.status','=',1],
            ['bid_mappers.allowed','like',"%".$this->user->writer_category_id."%"]
        ])
            ->join('orders','orders.id','=','bid_mappers.order_id')
//            ->whereIn('orders.style_id',$styles)
//            ->whereIn('orders.subject_id',$subjects)
            ->whereNotIn('bid_mappers.order_id',$bidded)->orderBy('deadline','asc')
            ->select('bid_mappers.*')
            ->get();
        return count($bidmappers);
    }

    public function takeOrder($bidMapper_id,Request $request){
        $bidMapper = BidMapper::findOrFail($bidMapper_id);
        $order = $bidMapper->order;
        if($request->method()=='POST'){
            //ASSIGN ORDER
            if($order->status == 1){
                return redirect("/writer/take/$bidMapper_id")->with('notice',['class'=>'error','message'=>'has already been assigned']);
            }
            $assign = $order->assigns()->create([
                'deadline'=>$request->deadline,
                'fine'=>'0.00',
                'bonus'=>'0.00',
                'user_id'=>$this->user->id,
                'amount'=>$request->amount,
                'bonus'=>'0.00',
            ]);
            $bidmapper = $order->bidMapper;
            $bidmapper->status=2;
            $bidmapper->update();
            $order->status = 1;
            $order->update();
            $mail = 'Hello Admin, <br/>
                '.$this->user->name.' Has taken order#<strong>'.$order->id.'</strong>
                Please check
';
            $this->emailer->sendAdminNote($mail);
            $this->emailer->sendAssignEmail($this->user,$assign,$order);
            return redirect("writer/order/$order->id/room/$assign->id")->with('notice',['class'=>'success','message'=>'Order has been assigned to you']);

        }

        return View('writer.take',[
            'order'=>$order,
            'bidmapper'=>$bidMapper
        ]);
    }

    public function deleteBid(Bid $bid){
        $bid->delete();
        return ['reload'=>true];
    }

    public function announcements(){
        $announcements = Announcement::where([
            ['published','=',1],
            ['target','=','writers']
        ])->orderBy('id','desc')->paginate(10);
        return view('writer.announcements',[
            'announcements'=>$announcements
        ]);
    }

    public function withdraw(Request $request){
        $user = $this->user;
        $is_manual = (int)$request->is_manual;
        $total_worked = $user->totalWorked($is_manual);
        $withdrawn = $user->totalWithdrawn($is_manual);
        $pending = $user->totalPending($is_manual);
        $fines = $user->totalFines($is_manual);
        $available = $total_worked-($withdrawn+$pending+$fines);
        $paypal = new AdaptivePayment();
        $paypal->request = $request;
        $pay_acc= $user->paymentAccounts()->where([
            ['website','like',$request->via]
        ])->first();
        if($request->via == 'paypal' && $pay_acc == null){
            return redirect()->back()->with('notice',['class'=>'danger','message'=>'Please add at least one payment account for '.$request->via]);
        }elseif($request->via == 'paypal'){
            $email = $pay_acc->email;
        }else{
            $payment = $user->payments()->create([
                'method'=>'manual',
                'amount'=>$available,
                'state'=>'PENDING'
            ]);
            $payment->is_manual = $request->is_manual;
            $payment->save();
            return redirect()->back()->with('notice',['class'=>'info','message'=>'Payment completed with status PENDING']);
        }
        $status = $paypal->payWriter($user,round($available,2),$email,(int)$request->is_manual);
        return redirect()->back()->with('notice',['class'=>'info','message'=>'Payment completed with status '.$status]);
    }


    public function addPayment(Request $request){
        $payment_info = $request->all();
        $this->user->paymentAccounts()->updateOrCreate([
            'website'=>$request->website
        ],$payment_info);
        return redirect("writer/payment")->with('notice',['class'=>'success','message'=>'Account added']);
    }

    public function executeFines(){

    }

}
