<?php

namespace App\Http\Controllers;

use App\Academic;
use App\AdditionalFeature;
use App\Currency;
use App\Dispute;
use App\Document;
use App\File;
use App\Language;
use App\Order;
use App\Payout;
use App\ProgressiveMilestone;
use App\Rate;
use App\Assign;
use App\Repositories\EmailRepository;
use App\Repositories\FileSaverRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaypalRepository;
use App\Repositories\WebsiteRepository;
use App\Style;
use App\User;
use App\WriterCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Subject;
use App\Urgency;
use App\BidMapper;
use URL;
use App\Website;
use App\Article;
class ClientController extends Controller
{
    //
    protected $user;
    protected $emailer;
    protected $active_status=1;
    protected $pending_status = 4;
    protected $closed_status = 6;
    protected $unassigned_status = 0;
    protected $webRepo;
    public function __construct(Request $request)
    {
        new MenuRepository($request);
        $this->user = Auth::user();
        $email_repo = new EmailRepository();
        $this->emailer = $email_repo;
        $this->webRepo = new WebsiteRepository();
    }

    public function index(Request $request){
        $orders = $this->user->orders()
            ->leftJoin('assigns','assigns.order_id','=','orders.id')
            ->leftJoin('assign_progresses','assign_progresses.assign_id','=','assigns.id')
            ->where('orders.paid','=',1)->whereIn('orders.status',[$this->active_status,$this->unassigned_status])
            ->orderBy('orders.deadline', 'asc')
            ->select('orders.*','assign_progresses.percent')
            ->paginate(10);
        if($this->webRepo->getWebsite()->author == 1){
            $tab = $request->tab;
            if(!$tab){
                $tab = 'approved';
            }
            if($tab == 'drafts'){
                 $user_id=Auth::user()->id;
        $draft_articles=Article::where('user_id',$user_id)
            ->where('status',0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
                $articles = $draft_articles;
            }
            if($tab == 'approved'){
             $user_id=Auth::user()->id;
        $approved_articles=Article::where('user_id',$user_id)
            ->where('status',2)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            $articles = $approved_articles;
            }
            if($tab == 'pending'){
                $user_id=Auth::user()->id;
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
            return view('client.articles.index',[
                'articles'=>@$articles,
                'tab'=>$tab
            ]);
        }
        $notices = [];
        if(!session('notices_shown')){
            $unpaid = Auth::user()->orders()->where('paid','=',0)->count();
            $notices  = [];
//            $notices[] = [
//                'title'=>'Affiliate Program',
//                'message'=>'Get $'.env('AFFILIATE_AMOUNT',40).' Added to your e-wallet for each friend you refer to us  <a class="btn btn-xs btn-info" href="'.url('stud/affiliate').'">Get Started</a>'
//            ];
            if($unpaid){
                $notices[] = [
                    'title'=>'Unpaid Orders',
                    'message'=>'You have '.$unpaid.' unpaid orders(s). Please pay for them. <a class="btn btn-info btn-xs load-page" href="'.url('stud/unpaid').'">View Unpaid</a>'
                ];
            }

        }
        return view('client.index',[
            'orders'=>$orders,
            'notices'=>$notices
        ]);
    }
    public function newOrder(Request $request){
        //$ip_api = "http://www.geoplugin.net/json.gp?ip=154.77.123.44";
      $ip_api = "http://www.geoplugin.net/json.gp?ip=".$request->ip();
        $country = @json_decode(@file_get_contents($ip_api))->geoplugin_countryName;
        $method = $request->method();
        if($method=='POST'){
            $file_ids = $request->file_ids;
            $repo = new OrderRepository();
            $order = new Order();
            $request->topic = $request->topic;
            $order = $order->exchangeArray($request);
            $order->discounted = $request->discounted;
            $order->amount = $repo->calculateCost($order);
            $add = $repo->getAdditionalFeaturesCost($request->feature_ids,$order->amount);
            $order->amount+=$add;
            $order->deadline = $repo->getDeadline($order);
            $order->partial = round($request->partial,0);
             if($request->paper_size){
              $order->paper_size = $request->paper_size;  
                }
              if($request->feature_ids){
                  $order->add_features = @json_encode($request->feature_ids);
             }   
             if($request->preview){
                  $request->session()->put('order',$order);  
                  return redirect("stud/preview");
             }
            $order->user_id = $this->user->id;
            $order = $repo->promote($order);
            $order->save();
            $fileRepo = new FileSaverRepository();
//            $fileRepo->uploadOrderFiles($order,$request);
            File::whereIn('id',$file_ids)->update([
                'order_id'=>$order->id,
                'user_id'=>Auth::user()->id,
                'allow_client'=>1,
                'file_for'=>'Order File'
            ]);
            $mailer = $this->emailer;
            if(Auth::user()->role=='admin'){
                if(isset($request->order_number)){
                    if(!Order::find($request->order_number)){
                        // $order->id = $request->order_number;
                    }
                }
                $order->save();
                $mapper = new BidMapper();
                $mapper->order_id = $order->id;
                $mapper->save();
                return redirect("order/$order->id");
            }else{
                 $order->save();
                // $order->id = $new_id;
                $mapper = new BidMapper();
                $mapper->order_id = $order->id;
                $mapper->save();
                $message = "Hello Admin<br/> A new order has been placed by ".$this->user->email." Order#$order->id.<br/>Please confirm";
              $this->emailer->sendAdminNote($message);
              $this->emailer->sendOrderplacedEmail($this->user,$order);
            }
            if($request->pay_now == 1 && $request->payment_method == 'paypal'){
                return $this->payDirect($order,$request);
            }
            elseif($request->pay_now == 1 && $request->payment_method == 'payza'){
                return redirect("stud/pay/$order->id?pay=payza");
            }elseif($request->pay_now == 1){
                return redirect("stud/pay/$order->id");
            }else{
                return redirect("/stud/order/$order->id");
            }

            $request->session()->set('order',$order);
            $request->session()->flash('notice',['class'=>'success','message'=>'Order has been brought under preview!']);
            return redirect("stud/preview");
        }
        $documents= Document::where('deleted','=','0')->get();
        $subjects = Subject::where('deleted','=','0')->get();
        $rates = Rate::where('deleted','=',0)->orderBy('hours','desc')->get();
        $academic_levels = Academic::where('deleted','=',0)->get();
        $styles = Style::where('deleted','=',0)->get();
        $languages = Language::where('deleted','=',0)->get();
        $website = $this->webRepo->getWebsite();
        $writer_categories = WriterCategory::where('deleted','=',0)->orderBy('amount','asc')->get();
        $currencies = Currency::where('deleted','=',0)->get();
        $additional_features = AdditionalFeature::orderBy('id','desc')->get();
        $settings = ['documents'=>$documents,'additional_features'=>$additional_features,'styles'=>$styles,'languages'=>$languages,'subjects'=>$subjects,'rates'=>$rates,'academics'=>$academic_levels,'writer_categories'=>$writer_categories,'currencies'=>$currencies];

        return view('client.new',[
            'documents'=>$documents,
            'subjects'=>$subjects,
            'academic_levels'=>$academic_levels,
            'settings'=>$settings,
            'styles'=>$styles,
            'languages'=>$languages,
            'website'=>$website,
            'writer_categories'=>$writer_categories,
            'currencies'=>$currencies,
            'additional_features'=>$additional_features,
            'country'=>$country
        ]);
    }

    public function saveTempFiles(Request $request){
        $fileSaver = new FileSaverRepository();
        $file = $request->file('file');
        $file = $fileSaver->uploadFile($file,true);
        return $file->id;
    }
    public function getNewOrderId(){
       $db = DB::select("select Auto_increment as id from information_schema.tables where table_name = 'orders'");
        $nx_id = $db[count($db)-1]->id;
        $new_id = $nx_id;
        return $new_id;
    }
    public function preview(Request $request){
        $order = $request->session()->get('order');
//        dd($order);
        $website = $website = $this->webRepo->getWebsite();
        if(!$order){
            return redirect("stud/new")->with('notice',['class'=>'error','message'=>'An error occurred, Order is not to be previewed anymore']);
        }
        return view('client.preview',[
            'order'=>$order,
            'country'=>'',
            'website'=>$website
        ]);
    }
    public function unpaid(){
        $orders = $this->user->orders()->orderBy('created_at','desc')->where([
            ['paid','=',0],
            ['status','!=',8]
        ])->paginate(10);
        return view('client.index',[
                'orders'=>$orders,
            'tab'=>'pending'
        ]);
    }
    public function active(){
 $orders = $this->user->orders()
            ->leftJoin('assigns','assigns.order_id','=','orders.id')
            ->leftJoin('assign_progresses','assign_progresses.assign_id','=','assigns.id')
            ->where('orders.paid','=',1)->whereIn('orders.status',[$this->active_status,$this->unassigned_status])
            ->orderBy('orders.deadline', 'asc')
            ->select('orders.*','assign_progresses.percent')
            ->paginate(10);
       return view('client.active',[
            'orders'=>$orders
        ]);
    }

    public function revision(){
        $orders = $this->user->orders()->where([
            ['paid','=',1],
            ['status','=',2]
        ])->orderBy('id','desc')->paginate(10);
        return view('client.revision',[
            'orders'=>$orders
        ]);
    }

    public function pending(){
        $orders = $this->user->orders()->where([
            ['paid','=',1],
            ['status','=',$this->pending_status]
        ])->orderBy('created_at','desc')->paginate(10);
        return view('client.pending',[
            'orders'=>$orders
        ]);
    }

    public function completed(){
        $orders = $this->user->orders()->orderBy('created_at','desc')->whereIn('status',[$this->pending_status])->orderBy('id','desc')->paginate(10);
        return view('client.index',[
            'orders'=>$orders,
            'tab'=>'completed'
        ]);
    }
    public function approved(){
        $orders = $this->user->orders()->orderBy('created_at','desc')->whereIn('status',[$this->closed_status])->orderBy('id','desc')->paginate(10);
        return view('client.index',[
            'orders'=>$orders,
            'tab'=>'approved'
        ]);
    }
    public function archived(){
        $orders = $this->user->orders()->orderBy('created_at','desc')->whereIn('status',[8])->orderBy('id','desc')->paginate(10);
        return view('client.index',[
            'orders'=>$orders,
            'tab'=>'archived'
        ]);
    }

    public function restoreArchived(Order $order){
        $order->status = 1;
        $bidmapper = $order->bidMapper;
        $bidmapper->status = 0;
        $bidmapper->update();
        $order->update();
        return redirect("stud/order/$order->id")->with('notice',['class'=>'success','message'=>'Order has been successfully restored']);

    }

    public function pay(Order $order, Request $request){
        $web_repo = new WebsiteRepository();
        $website = $web_repo->getWebsite();
        if($website->wallet){
            return view('client.prepay',[
                'order'=>$order
            ]);
        }
        $paypal = new PaypalRepository();
        $paypal->prepare($request,$order);
    }
    public function payDirect(Order $order, Request $request){
        $paypal = new PaypalRepository();
        $paypal->prepare($request,$order);
    }
    public function chargeWallet(Order $order, Request $request){
        $balance = $this->user->getBalance();
        if($balance<$order->amount){
            return redirect()->back()->with('notice',['class'=>'error','message'=>'Your balance is insufficient']);
        }else {
            $reference = 'account_payment_' . ($order->payments()->where([
                        ['via', 'like', 'account_pay']
                    ])->count() + 1);
            $payment = $order->payments()->create([
                'amount' => $order->amount,
                'txn_id' => $reference
            ]);
            $payment->via = 'account_pay';
            $payment->save();
            $order->paid = 1;
            $order->update();
            $bidmapper = $order->bidMapper;
            if($bidmapper->status == 0){
              $bidmapper->status = 1;
          }
            $bidmapper->allowed = json_encode([$order->writer_category_id]);
            $bidmapper->update();
            $order->paid = 1;
            $this->user->awardReferrer($payment);
            $this->emailer->sendAdminNote('A client (' . $order->user->email . ') Just paid up his/her order #' . $order->id, 'Payment Notice');
            return redirect("stud")->with('notice', ['class' => 'success', 'message' => 'Payment successful']);
        }
    }
    public function cancelPayment(Request $request){
        $request->session()->flash('notice',['class'=>'error','message'=>'Payment was interrupted, please try again later']);
        return redirect('stud/unpaid');
    }
    public function savePayment(Request $request){
        $method = $request->method();
        $paypal = new PaypalRepository();
        if($method=='GET'){
            $payerId = $request->PayerID;
            $paymentId = $request->paymentId;
            if(!$paymentId ||  !$payerId){
                return redirect('/stud/unpaid')->with('notice',["class"=>"error","message"=>"Payment was interrupted. Please try again later"]);
            }
            $paypal->request = $request;
            $response = $paypal->charge($payerId,$paymentId);
            $order_id = $response->transactions[0]->item_list->items[0]->sku;
            if(count(explode('_part_',$order_id))>1){
                $sku_arr = explode('_part_',$order_id);
                $order_id = $sku_arr[0];
                $mile_id = $sku_arr[1];
                $milestone = ProgressiveMilestone::find($mile_id);
                $milestone->paid = 1;
                $milestone->save();
            }else{
                $mile_id = 0;
            }
            $usd_amount = $response->transactions[0]->amount->total;
            $currency = $response->transactions[0]->amount->currency;
            $order = Order::find($order_id);
            if($order->currency){
                $usd_rate = $order->currency->usd_rate;
            }else{
                $usd_rate = 1;
            }
            $txn = $order->paypalTxns()->create([
                'amount'=>$usd_amount,
                'txn_id'=>$response->id,
                'state'=>$response->state,
                'create_time'=>$response->create_time,
                'currency'=>$currency,
                'usd_rate'=>$usd_rate
            ]);
            $txn->milestone_id = @$mile_id;
            $txn->save();
            if(!@$mile_id){
                foreach ($order->progressiveMilestones as $milestone){
                    $milestone->paid = 1;
                    $milestone->update();
                }
            }

            $email_repo = new EmailRepository();
           $email_repo->sendGeneralEmail('client_order_paid','Order Payment Succeeded',$order->user,$order);
            $message = "Hello Admin<br/> Order#$order->id has been paid for, amt= $usd_amount $currency";
            $this->emailer->sendAdminNote($message);
            $o_user = $order->user;
            $bidmapper= $order->bidMapper;
            if($bidmapper->status == 0){
                $bidmapper->status = 1;
            }
            $bidmapper->allowed = json_encode([$order->writer_category_id]);
            $bidmapper->update();
            $order->paid = 1;
            $deadline = Carbon::now();
            $deadline->addHours($order->rate->hours);
            $order->deadline=$deadline;
            $order->update();
            $this->user->awardReferrer($txn);
            return redirect("stud/order/$order->id")->with('notice',["class"=>"success","message"=>"Order payment succeeded"]);
        }
    }
    public function order(Order $order, Request $request){
        if(!$this->user->orders()->find($order->id)){
            return redirect('/stud')->with('notice',['class'=>'error','message'=>'You are not allowed to view this order!']);
        }
        if($order->bidMapper == null){
            try{
                $mapper = new BidMapper();
                $mapper->order_id = $order->id;
                $mapper->save();
            }catch(\Exception $e){

            }

        }
        $method = $request->method();
        if($method=='POST'){
            $file =$request->file('file');
            if($file) {
                $filename = $file->getClientOriginalName();
                $size = $file->getClientSize();
                $file_type = explode('.', $filename)[1];
                $year = date('Y');
                $month = date('M');
                $day = date('d');
                $path = 'uploads/' . $year . '/' . $month . '/' . $day . '/' . strtotime(date('h:i:s')) . '_' . time().'.'.$file_type;
                Storage::put(
                    $path,
                    file_get_contents($request->file('file')->getRealPath())
                );
               $order_file= $order->files()->create([
                    'user_id' => $this->user->id,
                    'filesize' => $size,
                    'filename' => $filename,
                    'file_type' => $file_type,
                    'file_for' => $request->filefor,
                    'path' => $path
                ]);
                $order_file->allow_client = 1;
                $order_file->save();
            }
            $mail = 'Hello Admin,<br/>
            A new File has been uploaded to order <strong>#'.$order->id.'</strong> <br/>Please Check
';
            $this->emailer->sendAdminNote($mail);
            $request->session()->flash('notice',['class'=>'success','message'=>'File uploaded successful']);
        }
        return view('client.order',[
            'order'=>$order
        ]);
    }

    public function edit(Order $order, Request $request){
        $method  = $request->method();
        if($method=='POST'){
            $order->topic = stripslashes($request->topic);
            $order->document = $request->document;
            $order->subject = $request->subject;
            $order->pages = $request->pages;
            $order->spacing = $request->spacing;
            $order->sources = $request->sources;
            $order->amount = $request->amount;
            $order->style = $request->style;
            $order->language = $request->language;
            $order->instructions = $request->instructions;
            $order->update();
            $orderRepo = new OrderRepository();
            $orderRepo->calculateCost($order);
            $request->session()->flash('notice',['class'=>'success','message'=>'Order has been brought under preview!']);
            return redirect("stud/preview/$order->id");
        }
        $urgencies = Urgency::get();
        $docs = Subject::where('doc_type','=','docs')->get();
        $subjects = Subject::where('doc_type','=','subject')->get();
        return view('client.edit',[
           'order'=>$order,
            'urgencies'=>$urgencies,
            'docs'=>$docs,
            'subjects'=>$subjects
        ]);
    }

    public function payLater(Request $request){
        $order = $request->session()->get('order');
        if($order == null){
            return redirect("stud/pending")->with('notice',['class'=>'danger','message'=>'You are trying to submit an order twice!']);
        }
        $orderRepo = new OrderRepository();
        $order = $orderRepo->promote($order);
        $order->status = 0;
        $order->user_id = $this->user->id;
        $webRepo = new WebsiteRepository();
        $website = $webRepo->getWebsite();
        $website_id = $webRepo->getWebsiteId();
        $order->website_id = $website_id;
        if ($order->discounted==null) {
            $order->discounted="";
        }
        if($website->admin_quote){
            $order->amount = 0;
        }
        $order->save();
        $mapper = new BidMapper();
        $mapper->order_id = $order->id;
        $mapper->save();
        $mailer = $this->emailer;
        $mailer->sendOrderplacedEmail($this->user,$order);
        $message = "Hello Admin<br/> A new order has been placed by ".$this->user->email." Order#$order->id.<br/>Please confirm";
        $this->emailer->sendAdminNote($message);
        $request->session()->remove('order');
        return redirect("stud/order/$order->id")->with('notice',['class'=>'info','message'=>'Your order has been successfully placed, please proceed to pay']);
    }

    public function payDeposit(Request $request){

        $order = $request->session()->get('order');
        if(!$order->discounted){
            $order->discounted = '';
        }
        if($order == null){
            return redirect("stud/pending")->with('notice',['class'=>'danger','message'=>'You are trying to submit an order twice!']);
        }
        $orderRepo = new OrderRepository();
       // $order = $orderRepo->promote($order);
        $order->status = 0;
        $order->user_id = $this->user->id;
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        $order->website_id = $website_id;
        $order->save();
        $order->partial = 1;
        $order->update();
        $mapper = new BidMapper();
        $mapper->order_id = $order->id;
        $mapper->save();
        $mailer = $this->emailer;
        $mailer->sendOrderplacedEmail($this->user,$order);
        $message = "Hello Admin<br/> A new order has been placed by ".$this->user->email." Order#$order->id.<br/>Please confirm";
        $this->emailer->sendAdminNote($message);
        $request->session()->remove('order');
        return redirect("stud/pay/$order->id");
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * Pay for an order using paypal
     */
    public function checkOut(Request $request){
        $order = $request->session()->get('order');
        if($order == null){
            return redirect("stud/pending")->with('notice',['class'=>'danger','message'=>'You are trying to submit an order twice!']);
        }
        $orderRepo = new OrderRepository();
        $order = $orderRepo->promote($order);
        $order->status = 0;
        $order->user_id = $this->user->id;
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        $order->website_id = $website_id;
        if ($order->discounted==null) {
            $order->discounted="";
        }
        $order->save();
        $mapper = new BidMapper();
        $mapper->order_id = $order->id;
        $mailer = $this->emailer;
        $mapper->save();
        $message = "Hello Admin<br/> A new order has been placed by ".$this->user->email." Order#$order->id.<br/>Please confirm";
        $this->emailer->sendAdminNote($message);
        $mailer->sendOrderplacedEmail($this->user,$order);
        $request->session()->remove('order');
        return redirect("stud/pay/$order->id");
    }

    /**
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *
     * Client action to approve a completed order
     */
    public function approveOrder(Order $order, Request $request){
        if($request->method() =='PUT'){
            if($order->is_manual){
                $assigns = $order->assigns()->where('status','=',3)->get();
            }else{
                $assigns = $order->assigns()->where('status','=',4)->get();
            }
           if(count($assigns)){
               $assign = $assigns[0];
               $assign->rating = $request->rating;
               if(!$request->rating){
                   $assign->rating = 4;
               }
               $assign->comments = $request->comments;
               $assign->update();
               $order->status = 6;
               $order->double_rating = 0;
               $order->update();
               $emailer = new EmailRepository();
               $emailer->notifyAdmin('notify_order_approval');
              return redirect("stud/tip/$assign->id")->with('notice',['class'=>'success','message'=>'You have successfully approved your order. Thank you so much for your feedback']);
           }
        }
        return view('client.approve',[
            'order'=>$order
        ]);
    }

    /**
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * Actions for sending order to disputes
     */
    public function disputeOrder(Order $order,Request $request){
        if($request->method()=='POST'){
            $asssign = $order->assigns()->where('status','=',4)->first();
            $action = $request->action;
            if($action=='other'){
                $action = $request->other;
            }
            $fileRepo = new FileSaverRepository();
            $files = $fileRepo->saveAssignFiles($request);
            $order->disputes()->create([
                'reason'=>$request->reason,
                'action'=>$action,
                'files'=>json_encode($files),
                'user_id'=>$this->user->id,
                'assign_id'=>$asssign->id
            ]);
            $order->status = 5;
            $order->update();
            $emailer = new EmailRepository();
            $mail = 'Hello Admin, <br/>
                '.$this->user->name.' Has launched a conflict on order#<strong>'.$order->id.'</strong>
                Please confirm and react accordingly
';
            $emailer->sendAdminNote($mail);
            return redirect('stud/disputes')->with('notice',['class'=>'success','message'=>'Order has been set to revision successfully. You we promise to resolve it ASAP']);
        }
        return view('client.dispute_order',[
            'order'=>$order
        ]);
    }

    /**
     * Show closed orders
     */

    public function showClosedOrders(){
        $orders = Order::where('status','=',6)->orderBy('id','desc')->paginate(10);
        return view('client.closed',[
            'orders'=>$orders
        ]);
    }

    /**
     * Show client disputes
     */

    public function showDisputes(){
        $disputes = $this->user->disputes()->where('status','=',0)->orderBy('id','asc')->paginate(10);
        return view('client.index',[
           'disputes'=>$disputes,
            'tab'=>'disputes'
        ]);
    }

    /**
     * login a user in order preview
     */

    public function login(Request $request){
        $email = $request->email;
        $password = $request->password;
        $web_repo = new WebsiteRepository();
        $website_id = $web_repo->getWebsiteId();
            if (Auth::attempt(['email' => $email, 'password' => $password, 'website_id' => $website_id])) {
                // The user is active, not suspended, and exists.
                return redirect('stud/preview');
                $this->notifyLoginSuccess($user,$request);
              }else{
                 return redirect('stud/preview')->withErrors([
                      'login_email'=>'Invalid email or password'
             ]);
        }
    }

    /**
     * Tip writer
     */
    public function tipWriter(Assign $assign,Request $request){
        if($request->method()=='POST'){
            $amount = $request->amount;
            $paypal = new PaypalRepository();
            $currency = $request->currency;
            $paypal->tipWriter($assign,$amount,$currency,$request);
        }
        $currencies = Currency::where('deleted','=',0)->get();
        return view('client.tip',[
            'assign'=>$assign,
            'currencies'=>$currencies,
            'order'=>$assign->order
        ]);
    }

    /**
     * Aprove and execute tip payment
     */

    public function tipSuccess(Request $request){
            $method = $request->method();
            $paypal = new PaypalRepository();
            $payerId = $request->PayerID;
            $paymentId = $request->paymentId;
            if(!$paymentId ||  !$payerId){
                return redirect('/stud/unpaid')->with('notice',["class"=>"error","message"=>"Payment was interrupted. Please try again later"]);
            }
            $paypal->request = $request;
            $response = $paypal->charge($payerId,$paymentId);
            $assign_id = $response->transactions[0]->item_list->items[0]->sku;
            $usd_amount = $response->transactions[0]->amount->total;
            $currency = $response->transactions[0]->amount->currency;
            $usd_rate = Currency::where('abbrev','like',$currency)->first()->usd_rate;
            $assign = Assign::find($assign_id);
            $assign->tip()->create([
                'amount'=>$usd_amount,
                'usd_amount'=>$usd_amount,
                'txn_id'=>$response->id,
                'state'=>$response->state,
                'create_time'=>$response->create_time,
                'currency'=>$currency,
                'usd_rate'=>$usd_rate
            ]);
        $emailer = new EmailRepository();
        $message = 'Hello Admin <br/> Writer '.$assign->id.' Has been tipped on order#'.$assign->order_id.' Amount '.$usd_amount.' '.$currency;
        $emailer->sendAdminNote($message);

        return redirect("stud/order/$assign->order_id#o_files")->with('notice',['class'=>'success','message'=>'Writer tipped successfully']);
    }

    public function cancelTip(Request $request){
        return redirect("stud/completed")->with('notice',['class'=>'error','message'=>'An Interruption occurred while capturing order tip']);
    }

    public function getOrderCounts(){

    }

    public function preferredWriter(Request $request){
        $writer_id = $request->writer_id;
        $status = 'false';
        if(User::where([
            ['id','=',$writer_id],
            ['role','like','writer']
        ])->first()){
            $order = $request->session()->get('order');
            $order->writer_id = $writer_id;
            $request->session()->set('order',$order);
            $status = 'true';
        }
        return $status;
    }

    public function addPages(Order $order,Request $request){
        $pages = $request->pages;
        $order->pages = $order->pages+=$pages;
        $repo = new OrderRepository();
        $cost = $repo->calculateCost($order);
       $order->amount = $cost;
        $order->update();
        $this->emailer->sendAdminNote('Hey Admin, More Pages have been added to order#'.$order->id.' Please check');
        return redirect("stud/order/$order->id")->with('notice',['class'=>'info','message'=>'More pages added, please pay the pending amount']);
    }

    public function addInstructions(Order $order,Request $request){
        $instructions = $request->instructions;
        $order->instructions = $instructions;
        $order->update();
        $this->emailer->sendAdminNote('More Instructions have been added to order#'.$order->id.' Please check');
        return redirect("stud/order/$order->id")->with('notice',['class'=>'info','message'=>'More instructions added!']);
    }

    public function addSources(Order $order,Request $request){
        $sources = $request->sources;
        $order->sources+= $sources;
        $order->update();
        $this->emailer->sendAdminNote('More sources have been added to order#'.$order->id.' Please check');
        return redirect("stud/order/$order->id")->with('notice',['class'=>'info','message'=>'More Sources added!']);
    }

    public function addHours(Order $order,Request $request){
        $hours = $request->hours;
//        dd($hours);
        $deadline = Carbon::createFromTimestamp(strtotime($order->deadline));
        $deadline->addHours($hours);
        $order->deadline = $deadline;
        $order->update();
        $this->emailer->sendAdminNote('More pages have been added to order#'.$order->id.' Please check');
        return redirect("stud/order/$order->id")->with('notice',['class'=>'info','message'=>'Deadline Extended!']);
    }

    public function addMilestone(Order $order,Request $request){
//        dd($request->all());
        if($request->pages<1){
            return ['Please specify the no. of pages'];
        }
        $all_pages = $order->progressiveMilestones()->sum('pages');
        $order_pages = $order->pages;
        $remaining_pages = $order_pages-$all_pages;
        if($request->id){
            $toreplace = $order->progressiveMilestones()->find($request->id)->pages;
            $remaining_pages+=$toreplace;
        }

        if($request->pages > $remaining_pages){
            return ['Pages exceed order pages, please ask admin to add more pages'];
        }


//        dd($request->pages/$order->pages);
        $re_amt = ($request->pages/$order->pages)*$order->amount;
        $re_amt = round($re_amt,2);
        $request->amount = $re_amt;
        $order_cost = $order->amount;
        $post_data = $request->all();
        $post_data['amount'] = $re_amt;
        if($request->id){
            $mll = $order->progressiveMilestones()->find($request->id);
            $previous_amt = $mll->amount;
            $mll->amount = 0;
            $mll->update();
        }
        $used = $order->progressiveMilestones()->sum('amount');
        $latest_milestone = $order->progressiveMilestones()->orderBy('id','desc')->first();
        if($latest_milestone){
            $least_deadline = Carbon::createFromTimestamp(strtotime($latest_milestone->deadline));
        }else{
            $least_deadline = Carbon::now();
        }
        $remaining_amount = $order_cost-$used;
        $deadline = Carbon::createFromTimestamp(strtotime($request->deadline));
        if($deadline<$least_deadline || $deadline<Carbon::now()){
            if($request->id){
                $mll->amount = $previous_amt;
                $mll->update();
            }
            return ['Milestone deadline should not be less than right now or previous deadline '];

        }
        $previous_costs = $order->progressiveMilestones()->sum('amount');
        $paid = $order->paypalTxns()->sum('amount');
        $milestone = $order->progressiveMilestones()->updateOrCreate(['id'=>$request->id],$post_data);
        $previous_costs+=$milestone->amount;
        if($previous_costs<$paid){
            $milestone->paid = 1;
            $milestone->update();
        }
        $this->emailer->sendAdminNote('A milestone has been Added/Updated in order#'.$order->id.' Please check');
        return ['redirect'=>URL::to("stud/order/$order->id")];
    }
    function notifyLoginSuccess($user,Request $request){
        $ip = $request->ip();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $known_device = $user->devices()->where([
            ['ip_address','=',$ip],
            ['HTTP_USER_AGENT','like',$user_agent]
        ])->first();
        if(!$known_device){
            $url = "http://www.geoplugin.net/json.gp?ip=$ip";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, null);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));
            $content = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $json_response = null;
            if($status==200 || $status==201) {
                $json_response = json_decode($content);
                $user->devices()->create([
                    'ip_address' => $ip,
                    'HTTP_USER_AGENT' => $user_agent,
                    'country' => $json_response->geoplugin_countryName
                ]);
                $emailer = new EmailRepository();
                $message = 'Hello ' . $user->name . '(' . $user->email . '),<br/>
                <strong>A new Device was used to sign in to your account </strong><br/>
                <strong><u>More Details..</u></strong>
                <table border="1">
                    <tr>
                        <th>IP</th>
                        <td>' . $ip . '</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>' . $json_response->geoplugin_countryName . '</td>
                    </tr>
                    <tr>
                        <th>Device</th>
                        <td>' . $user_agent . '</td>
                    </tr>
                </table>
                <p style="color: red;">If this was not you, please change your password to a more secure one as soon as possible</p>
              ';
                $emailer->sendMail($user->email,"New Device Sign in",$message);
            }
        }else{
            $known_device->ip_address = $ip;
            $known_device->HTTP_USER_AGENT =$user_agent;
            $known_device->update();
        }
    }

    public function deleteOrder(Order $order){
        $order->delete();
        $order->bidMapper->delete();
        return ['reload'=>true];
    }

    public function redeemPoints(Request $request,$points = null){
        $rate = $this->user->website->getRedeemRate();
        if(!$points){
            $points = $this->user->getPoints();
        }else{
            if($points>$this->user->getPoints()){
                return false;
            }
        }
        if($points>$rate){
            $earned = round($points/$rate,2);
//            'amount','usd_rate','','currency_id','via
            $via = 'redeem points';
            $reference = 'redeem_points_'.($this->user->accountTopUps()->where([
                ['via','like',$via]
                ])->count()+1);
            $this->user->accountTopUps()->create([
                'amount'=>$earned,
                'via'=>'redeem points',
                'usd_rate'=>1,
                'reference'=>$reference,
                'redeemed_points'=>$points
            ]);
            return ['reload'=>true];
        }else{
            return ['Redeem amount should more that 1 USD'];
        }
    }
    public function redeemPay(Order $order,Request $request){
        if($this->redeemPoints($request,$request->points)){
            return $this->chargeWallet($order,$request);
        }else{
            return redirect()->back()->with('notice',['class'=>'error','message'=>'A Unexpected error occured. please try again later']);
        }
    }
    public function prepareTopUp(Request $request){
        $amount = $request->amount;
        $amount = round($amount,2);
        if($amount<1){
            return redirect()->back()->with('status',['class'=>'error','info'=>'Amount should be more than 1']);
        }
        $paypal = new PaypalRepository();
        $paypal->topUp($request,$amount);
    }

    public function executeTopUp(Request $request){
        $user = $this->user;
        $paypal = new PaypalRepository();
        $payerId = $request->PayerID;
        $paymentId = $request->paymentId;
        if(!$paymentId ||  !$payerId){
            return redirect('/stud')->with('notice',["class"=>"error","message"=>"Payment was interrupted. Please try again later"]);
        }
        $paypal->request = $request;
        $response = $paypal->charge($payerId,$paymentId);
        $assign_id = $response->transactions[0]->item_list->items[0]->sku;
        $usd_amount = $response->transactions[0]->amount->total;
        $currency = $response->transactions[0]->amount->currency;
        $usd_rate = 1;
        $user->accountTopUps()->create([
            'amount'=>$usd_amount,
            'reference'=>$response->id,
            'currency'=>$currency,
            'usd_rate'=>$usd_rate,
            'via'=>'account_pay'
        ]);
        $this->emailer->sendAdminNote('A client ('.$user->email.') Just topped up his/her account with $'.$usd_amount,'Payment Notice');
        return redirect('/stud')->with('notice',["class"=>"success","message"=>"Payment successful"]);
    }

    public function eWallet(){
        return view('client.wallet',[
            'user'=>Auth::user()
        ]);
    }

   public function viewArticle(Article $article){
        $user_id = Auth::user()->id;
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
            'article'=>$article,
            'stats'=>$article->statistics()->paginate(10),
            'tab'=>'view'
        ]);
    }

    public function deleteArticle(Article $article){
        if($article->user_id == Auth::user()->id){
            $article->delete();
            return ['reload'=>true];
        }else{
            return ['An error occured while deleting the article'];
        }
    }
}
