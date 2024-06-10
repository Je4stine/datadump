<?php
namespace App\Http\Controllers;

use App\Article;
use App\Assign;
use App\BidMapper;
use App\Dispute;
use App\Document;
use App\Order;
use App\Payout;
use App\PostWebsite;
use App\ProgressiveMilestone;
use App\PublishedArticle;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\Repositories\WebsiteRepository;
use App\Style;
use App\Urgency;
use App\Website;
use App\WriterCategory;
use Illuminate\Foundation\Auth\User;
use Nexmo\Client;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Response;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Repositories\OrderRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Repositories\FileSaverRepository;
use App\Bid;
use App\Subject;
use App\Rate;
use App\Academic;
use App\Language;
use URL;
use App\Currency;
use App\OrderMessage;
use Carbon\Carbon;

/**
 * Class OrderController
 * @package App\Http\Controllers
 * Assign Keys
 * completed = 4
 * pending = 3
 * active = 0;
 * revision= 2;
 * active  = 1;
 *
 *
 */
class OrderController extends Controller
{
    protected $user;
    protected $orders;
    protected $fileSaver;
    protected $emailer;
    protected $assign_active = 0;
    protected $assign_pending = 3;
    protected $assign_revision = 2;
    protected $assign_completed = 4;
    protected $order_working = 1;
    protected $order_new = 0;
    protected $bid_assigned = 1;
    protected $order_confirmed = 4;
    protected $cancelled_status = 7;
    protected $archived_status = 8;
    protected $webRepo;
    public function __construct(OrderRepository $orders, FileSaverRepository $fileSaverRepository, Request $request){
        $this->middleware('auth');
        $user = Auth::user();
        $this->user = $user;
       $this->orders = $orders;
        $this->fileSaver = $fileSaverRepository;
        $emailRepo = new EmailRepository();
        $this->emailer = $emailRepo;
        $this->webRepo = new WebsiteRepository();
       new MenuRepository($request);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = Assign::where([
            ['status','=',$this->assign_active],
            ['is_manual','=',0]
        ])->count();
        $partial_pending = Order::whereIn('id',$this->getPartialIds()->pending)->count();
        $partial_completed = Order::whereIn('id',$this->getPartialIds()->complete)->count();
        $completed = Assign::where([
            ['status','=',$this->assign_completed],
            ['is_manual','=',0]
        ])->count();
        $revision = Assign::where([
            ['status','=',$this->assign_revision],
        ])->count();
        $pending = Assign::where([
            ['status','=',$this->assign_pending],
            ['is_manual','=',0]
        ])->count();
        $unpaid = Order::where('paid','=',0)->count();
        $admins = User::where('role','like','admin')->count();
        $pending_articles = Article::where('status',1)->count();
        $published_articles = Article::where('status',2)->count();
        $client_website_ids = Website::where('author',0)->lists('id');
        $author_website_ids = Website::where('author',1)->lists('id');
        $active_bids = BidMapper::
        join('orders','orders.id','=','bid_mappers.order_id')
        ->where([
            ['bid_mappers.status','=',1],
            ['orders.deleted_at','=',null]
        ])->count();
        $inactive_bids = BidMapper::join('orders','orders.id','=','bid_mappers.order_id')
        ->where([
            ['bid_mappers.status','=',0],
            ['orders.deleted_at','=',null]
        ])->count();
        $applications = User::where([
            ['role','LIKE','writer'],
            ['status','=',0]
        ])->count();
        $writers = User::where([
            ['role','like','writer'],
            ['status','=',1],
        ])->count();
        $clients = User::where([
            ['role','like','client'],
        ])
            ->whereIn('website_id',$client_website_ids)
            ->count();
        $authors = User::where([
            ['role','like','client'],
        ])
            ->whereIn('website_id',$author_website_ids)
            ->count();
        $suspended = User::where('suspended','=',1)->orderBy('id','desc')->count();
        $disputes = Dispute::where('status','=',0)->count();
        $payouts = Payout::join('users','users.id','=','payouts.user_id')
            ->whereIn('payouts.state',['CREATED','pending'])
            ->select('payouts.*','users.id as user_id','users.email')
            ->orderBy('id','desc')->paginate(10);

        return View('order.index',[
                'active'=>$active,
            'completed'=>$completed,
            'revision'=>$revision,
            'pending'=>$pending,
            'unpaid'=>$unpaid,
            'writers'=>$writers,
            'admins'=>$admins,
            'clients'=>$clients,
            'disputes'=>$disputes,
            'applications'=>$applications,
            'suspended'=>$suspended,
            'writers'=>$writers,
            'clients'=>$clients,
            'active_bids'=>$active_bids,
            'inactive_bids'=>$inactive_bids,
            'partial_pending'=>$partial_pending,
            'partial_completed'=>$partial_completed,
            'payouts'=>$payouts,
            'pending_articles'=>$pending_articles,
            'published_articles'=>$published_articles,
            'authors'=>$authors
            ]);

    }
    /**
     * show new orders
     */
    public function newOrders(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $id = $key;
                $orders = Order::where([
                    ['status','=',$this->order_new],
                    ['id','LIKE',$id]
                ])->orderBy('id','desc')->paginate(10);
            }else{
                $orders = Order::where([
                    ['status','=',$this->order_new],
                    ['topic','LIKE','%'.$key.'%']
                ])->orderBy('id','desc')->paginate(10);
            }
        }else{
            $orders = Order::where([
                ['status','=',$this->order_new],
            ])->orderBy('id','desc')->paginate(10);
        }
        return View('order.new',[
            'orders'=>$orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $documents= Document::where('deleted','=','0')->get();
        $subjects = Subject::where('deleted','=','0')->get();
        $rates = Rate::where('deleted','=',0)->get();
        $academic_levels = Academic::where('deleted','=',0)->get();
        $styles = Style::where('deleted','=',0)->get();
        $languages = Language::where('deleted','=',0)->get();
        $website = @Website::where('home_url','LIKE',URL::to('/'))->get()[0];
        $writer_categories = WriterCategory::where('deleted','=',0)->orderBy('amount','asc')->get();
        $settings = ['documents'=>$documents,'styles'=>$styles,'languages'=>$languages,'subjects'=>$subjects,'rates'=>$rates,'academics'=>$academic_levels,'writer_categories'=>$writer_categories];
        $method = $request->method();
        if($method=='POST'){
            $repo = new OrderRepository();
            $order = new Order();
            $order = $order->exchangeArray($request);
            $order->amount = $repo->calculateCost($order);
            $order->deadline = $repo->getDeadline($order);
            $request->session()->set('order',$order);
            $request->session()->flash('notice',['class'=>'success','message'=>'Order has been brought under preview!']);
            return redirect("stud/preview");
        }
        return view('client.new',[
            'documents'=>$documents,
            'subjects'=>$subjects,
            'academic_levels'=>$academic_levels,
            'settings'=>$settings,
            'styles'=>$styles,
            'languages'=>$languages,
            'website'=>$website,
            'writer_categories'=>$writer_categories
        ]);
    }
    public function toRoom(Assign $assign){
        dd($assign);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $webRepo = new WebsiteRepository();
        $website_id = $webRepo->getWebsiteId();
        $order = $request->session()->get('order');
        $orderRepo = new OrderRepository();
        $order = $orderRepo->promote($order);
        $order->status = 0;
        $order->user_id = $this->user->id;
        $order->website_id = $website_id;
        if(!isset($order->discounted)){
            $order->discounted = 0;
        }
        $order->save();
        $mapper = new BidMapper();
        $mapper->order_id = $order->id;
        $mailer = $this->emailer;
        $mailer->sendOrderplacedEmail($this->user,$order);
        $mapper->save();
        $request->session()->remove('order');
        return redirect("/order/$order->id")-> with('notice',['class'=>'success','message'=>'Order has been added successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    $order = Order::find($id);
    if(!$order){
        $message = OrderMessage::where('order_id',$id)->delete();
                return redirect("/order")-> with('notice',['class'=>'error','message'=>'Order does not exist. Message discarded']);

    }
        return View('order.view',[
            'order'=>$order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        return View('order.edit',[
           'order'=>$order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
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
        $order->deadline = $request->deadline;
        $order->update();
        $files = $this->orders->getOrderFiles($order->id);
       $request->session()->flash('notice',['class'=>'info','message'=>'Order has been updated!']);
        return View('order.view',[
            'order'=>$order,
            'files'=>$files
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Upload a new file to order
     */
    public function addFile(Request $request,Order $order){
            $file =$request->file('file');
        if($file) {
            $filename = $file->getClientOriginalName();
            $size = $file->getClientSize();
            $file_type = explode('.', $filename)[1];
            $year = date('Y');
            $month = date('M');
            $day = date('d');
            $path = 'uploads/' . $year . '/' . $month . '/' . $day . '/' . strtotime(date('h:i:s')) . '_' . $filename;
            Storage::put(
                $path,
                file_get_contents($request->file('file')->getRealPath())
            );
            $order->files()->create([
                'user_id' => $this->user->id,
                'filesize' => $size,
                'filename' => $filename,
                'file_type' => $file_type,
                'file_for' => $request->filefor,
                'path' => $path
            ]);
        }
        $files = $this->orders->getOrderFiles($order->id);
        $request->session()->flash('notice',['class'=>'success','message'=>'File uploaded successfully']);
        return View('order.view',[
            'order'=>$order,
            'files'=>$files
        ]);
    }

    /**
     * @param Order $order
     * @param File $file
     * @return mixed
     *
     * Download an order file
     */

    public function downloadFile(Order $order,File $file){
        $path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$file->path;
        $filename = $file->filename;
        $filetype = $file->file_type;
        $headers = array(
            'Content-Type'=>$filetype
        );
        Return Response::download($path,$filename,$headers);
        $files = $this->orders->getOrderFiles($order->id);
    }

    /**
     * Search for orders
     */
    public function search(Request $request){
        $search = $request->search;
       if(is_numeric($search)){
           $orders = Order::where('id','=',$search)->get();
       }else{
           $orders = Order::where('topic','like','%'.$search.'%')->get();
       }
        echo json_encode($orders);
    }
    /**
     * View a order Bid
     */

    public function showBid(Order $order,Bid $bid){
        return View('order.bid',[
           'order'=>$order,
            'bid'=>$bid
        ]);
    }
    public function forceAssign(Request $request ){
        if($request->method()=='GET') {
            $writers = User::where([
                ['role', 'like', 'writer'],
                ['status', 'like', 1]
            ])->get();
            echo $writers;
            die();
        }
        $user = \App\User::findOrFail($request->writer_id[0]);
        $order = Order::findOrFail($request->id);
        if($order->status == $this->order_working){
            return redirect("/order/$order->id")->with('notice',['class'=>'error','message'=>'has already been assigned']);
        }
        $assign = $order->assigns()->create([
            'deadline'=>$request->deadline,
            'fine'=>'0.00',
            'bonus'=>$request->bonus,
            'user_id'=>$user->id,
            'amount'=>$request->amount,
            'bonus'=>$request->bonus,
        ]);
        $assign->is_manual = $order->is_manual;
        $assign->update();
        $bidmapper = $order->bidMapper;
        if($bidmapper){
            $bidmapper->status=2;
            $bidmapper->update();
            $order->status = $this->order_working;
            $order->update();
        }
        $order->status = $this->order_working;
        $order->update();
        $this->emailer->sendAssignEmail($user,$assign,$order);
        return redirect("order/$order->id/room/$assign->id")->with('notice',['class'=>'success','message'=>'Order Assigned Successfully']);
    }
    /**
     * Assign order to bid
     */
    public function assignOrder(Order $order, Bid $bid, Request $request){
        if($order->status==$this->order_working){
            $request->session()->flash('notice',['class'=>'error','message'=>'Order has been assigned to another writer!']);
            return redirect("/order/$order->id")->with('notice',['class'=>'error','message'=>'has already been assigned']);
        }
//        Assign::where('order_id','=',$order->id)->update(
//            ['status'=>$this->assign_active]
//        );
        $bid->status = $this->bid_assigned;
        $bid->save();

        $bidmapper = $order->bidMapper;
        if($bidmapper){
            $bidmapper->status=2;
            $bidmapper->update();
        }
        $assign = $this->orders->assignBid($bid,$request,$order);
        $assign->is_manual = $order->is_manual;
        $assign->update();
        $order->status = $this->order_working;
        $order->update();
        $this->emailer->sendAssignEmail($assign->user,$assign,$order);
        $request->session()->flash('notice',['class'=>'success','message'=>'writer assigned order successfully ']);
        return redirect("/order/$order->id");
    }

    /**
     * Get active orders
     */
    public function activeOrders(Request $request){
         if($request->search){
             $key = $request->search;
             if(is_numeric($key)){
                 $assigns = Assign::where('assigns.status','=',$this->assign_active)
                     ->select('assigns.*')
                     ->join('orders','orders.id','=','assigns.order_id')
                     ->where('orders.id','like',$key)
                     ->where('orders.is_manual','=',0)
                     ->orderBy('orders.created_at','desc')->paginate(10);
             }else{
                 $assigns = Assign::where('assigns.status','=',$this->assign_active)
                     ->select('assigns.*')
                     ->join('orders','orders.id','=','assigns.order_id')
                     ->where('orders.is_manual','=',0)
                     ->where('orders.topic','like',"%$key%")
                     ->orderBy('orders.created_at','desc')->paginate(10);
             }
         }else{
             $assigns = Assign::where('assigns.status','=',$this->assign_active)
                 ->select('assigns.*')
                 ->where('orders.is_manual','=',0)
                 ->join('orders','orders.id','=','assigns.order_id')
                 ->orderBy('orders.created_at','desc')->paginate(10);
         }

        return View('order.active',[
            'assigns'=>$assigns
        ]);
    }

    /**
     * Get completed orders
     */

    public function completedOrders(Request $request){
        $this->orders->autoApprove();
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $assigns = Assign::where('assigns.status','=',$this->assign_completed)
                    ->select('assigns.*')
                    ->join('orders','orders.id','=','assigns.order_id')
                    ->orderBy('orders.id','desc')
                    ->where([
                        ['orders.id','=',$key],
                        ['orders.deleted_at','=',null]
                    ])
                    ->where('orders.is_manual','=',0)
                    ->orderBy('orders.created_at','desc')->paginate(10);
            }else{
                $assigns = Assign::where('assigns.status','=',$this->assign_completed)
                    ->select('assigns.*')
                    ->join('orders','orders.id','=','assigns.order_id')
                   ->orderBy('orders.id','desc')
                    ->where([
                        ['orders.topic','like',"%$key%"],
                        ['orders.deleted_at','=',null]
                    ])
                    ->where('orders.is_manual','=',0)
                    ->orderBy('orders.created_at','desc')->paginate(10);
            }
        }else{
            $assigns = Assign::where('assigns.status','=',$this->assign_completed)
                ->select('assigns.*')
                ->orderBy('orders.id','desc')
                ->join('orders','orders.id','=','assigns.order_id')
                ->where([
                    ['orders.deleted_at','=',null]
                ])
                ->where('orders.is_manual','=',0)
                ->orderBy('orders.created_at','desc')->paginate(10);
        }
        return View('order.completed',[
            'assigns'=>$assigns
        ]);
    }


    /**
     * Get revision orders
     */
    public function revisionOrders(){

        $assigns = Assign::where('status','=',$this->assign_revision)
            ->where('is_manual','=',0)
            ->orderBy('created_at','desc')->paginate(10);
        return View('order.revision',[
            'assigns'=>$assigns
        ]);
    }

    /**
     * Chat room for assigned order
     */
    public function orderRoom(Order $order, Assign $assign,Request $request){
        $method = $request->method();
        if($method=='POST'){
            $type = $request->type;
            $filesaver = $this->fileSaver;
           $paths =  $filesaver->saveAssignFiles($request,$assign,$type);
            $files = $filesaver->uploaded;
            if($request->complete){
                $assign->status = 3;
                $assign->save();
                $order->status= 3;
                $order->save();
            }
            $writer = $assign->user;
            $mail = "Hello $writer->name,<br/> A new file has been uploaded to your active order#$order->id, please check";
            $this->emailer->sendEmailNote($writer,'File upload notice',$mail,$files);
            $request->session()->flash('notice',['class'=>'info','message'=>'Order file has been added ']);
        }
        $files = File::where([
            ['assign_id','=',0],
            ['order_id','=',$order->id]
        ])->orWhere([
            ['assign_id','=',$assign->id]
        ])->orderBy('id','asc')->get();
        return View('order.room.index',[
            'order'=>$order,
            'assign'=>$assign,
            'files'=>$files
        ]);
    }

    /**
     * Extend an order
     */
    public function showExtend(Order $order,Assign $assign){

        return View('order.extend',[
            'order'=>$order,
            'assign'=>$assign
        ]);
    }

    /**
     * Save an assigned order adjustment
     */
    public function extendOrder(Order $order,Assign $assign,Request $request){
        $assign->deadline = $request->deadline;
        $assign->amount = $request->amount;
        $assign->bonus = $request->bonus;
        $assign->save();
        return redirect("order/$order->id/room/$assign->id")->with('notice',['class'=>'success','message'=>'Changes have been saved!']);
    }

    /**
     * Show pending orders
     */
    public function showPending(){
        $assigns = Assign::where('status','=',$this->assign_pending)
        ->where('is_manual',0)
            ->orderBy('created_at','desc')->paginate(10);
        return View('order.pending',[
            'assigns'=>$assigns
        ]);
    }
    /**
     * set order to pending
     */
    public function setPending(Order $order, Assign $assign, Request $request){
        $method = $request->method();
        if($method=='GET'){
            return View('order.setpending',[
                'order'=>$order,
                'assign'=>$assign
            ]);
        }
        if($method=='PUT'){
            $assign->status = $this->assign_pending;
            $order->status = 1;
            $order->update();
            $assign->update();
            return redirect()->to('/order/pending')->with('notice',['class'=>'info','message'=>'Order has been set as pending!']);
        }

    }
    /**
     * Send order to revision
     *
     */
    public function sendRevision(Order $order, Assign $assign, Request $request){
        $method = $request->method();
        if($method=='POST'){
            $fileSaver = $this->fileSaver;
            $fileSaver->saveAssignFiles($request,$assign);
            $assign->revisionMessages()->create([
                'message'=>$request->message
            ]);
            $assign->deadline = $request->deadline;
            $assign->status = $this->assign_revision;
            $assign->save();
            $order->save();
            $emailer = $this->emailer;
            $emailer->sendGeneralEmail('writer_revision','Revision Notice',$assign->user,$order);
            $request->session()->flash('notice',['class'=>'success','message'=>'Order has been returned back to revision']);
            return redirect("order/$order->id/room/$assign->id");
        }
        return View('order.setrevision',[
            'order'=>$order,
            'assign'=>$assign
        ]);
    }

    /**
     * Confirm completed order
     */
    public function confirmOrder(Order $order, Assign $assign, Request $request){
        $method = $request->method();
        if($method=='GET'){
            if($assign->is_manual){
                $order->status = $this->order_confirmed;
                $assign->status = $this->assign_completed;
                $order->save();
                $assign->save();
                return redirect("manual/pending")->with('status',['class'=>'success','message'=>'Order has been confirmed']);
            }
            return View('order.confirm',[
                'order'=>$order,
                'assign'=>$assign
            ]);
        }
        if($method=='PUT'){
            $fileRepo = new FileSaverRepository();
            $fileRepo->submitAssignFiles($assign);
            $assign->comments = $request->comments;
           $assign->rating = $request->rating;
           $order->status = $this->order_confirmed;
            $assign->status = $this->assign_completed;
            $order->save();
            $assign->save();
            $files = File::where([
                ['assign_id','=',0],
                ['order_id','=',$order->id]
            ])->orWhere([
                ['assign_id','=',$assign->id]
            ])->orderBy('id','asc')->get();
            $emailer = $this->emailer;
            $emailer->sendEmailNote($order->user,"Order Completion Notification",'
                Hi {name},<br/>
                Your order has been successfully completed. Please login to your account and confirm.<br/>
                With Regards,<br/>
                Support
            ',$files);
            $request->session()->flash('notice',['class'=>'success','message'=>'Order has been confirmed as completed, all the writer files have been submitted to client']);
            return redirect()->to('/order/completed')->with('notification',['class'=>'success','info'=>'Order has been marked as completed and writer rated.']);
        }
    }

    /**
     * view and manipulate writers
     */

    public function writers(){
        $writers = User::where(['role'=>'writer'])->paginate(10);
        return view('order.writers',[
            'writers'=>$writers
        ]);
    }

    /**
     * View single writer
     */
    public function viewWriter(User $user){
        $id = $user->id;
        $assigns = Assign::where([
            ['status','>',2],
            'user_id'=>$id
        ])->get();
        return view('order.writer',[
                'user'=>$user,
                'assigns'=>$assigns
        ]);
    }
    /**
     * change user layout
     */
    public function changeLayout(Request $request){
        $layout = $request->layout;
        $user = $this->user;
        $user->layout = $layout;
        $user->update();
        return redirect("order")->with('notification',['class'=>'success','info'=>'User profile changed successfully']);
    }
    /**
     * Show active orders (allowed for bidding)
     */
    public function activeBids(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $bidMappers = BidMapper::where('bid_mappers.status','=',1)
                    ->where('orders.id','like',$key)
                    ->join('orders','orders.id','=','bid_mappers.order_id')
                    ->select('bid_mappers.*','orders.status as o_status')
                    ->orderBy('orders.created_at','desc')->paginate(20);
            }else{
                $bidMappers = BidMapper::where('bid_mappers.status','=',1)
                    ->where('orders.topic','like',"%$key%")
                    ->join('orders','orders.id','=','bid_mappers.order_id')
                    ->select('bid_mappers.*','orders.status as o_status')
                    ->orderBy('orders.created_at','desc')->paginate(20);
            }
        }else{
            $bidMappers = BidMapper::where('bid_mappers.status','=',1)
                ->join('orders','orders.id','=','bid_mappers.order_id')
                ->select('bid_mappers.*','orders.status as o_status')
                ->orderBy('orders.created_at','desc')->paginate(20);

        }
        return view('order.bids.active',[
            'bidmappers'=>$bidMappers
        ]);
    }
    /**
     * show inactive orders (not yet allowed for bidding)
     */

    public function inActiveBids(Request $request){
        if($request->search){
            $key = $request->search;
            if(is_numeric($key)){
                $bidMappers = BidMapper::where('bid_mappers.status','=',0)
                    ->where('orders.id','like',$key)
                    ->join('orders','orders.id','=','bid_mappers.order_id')
                    ->select('bid_mappers.*')
                    ->orderBy('orders.created_at','desc')->paginate(20);
            }else{
                $bidMappers = BidMapper::where('bid_mappers.status','=',0)
                    ->where('orders.topic','like',"%$key%")
                    ->join('orders','orders.id','=','bid_mappers.order_id')
                    ->select('bid_mappers.*')
                    ->orderBy('orders.created_at','desc')->paginate(20);
            }
        }else{
            $bidMappers = BidMapper::where('bid_mappers.status','=',0)
                ->join('orders','orders.id','=','bid_mappers.order_id')
                ->select('bid_mappers.*')
                ->orderBy('orders.created_at','desc')->paginate(20);

        }
        return view('order.bids.inactive',[
            'bidmappers'=>$bidMappers
        ]);
    }

    /**
     * enable order for bidding
     */

    public function activateBid($id,Request$request){
        $bidmapper = BidMapper::findOrFail($id);
        $method = $request->method();

        if($method=='POST'){
            $bidmapper->deadline = $request->deadline;
            $bidmapper->bid_amount = $request->amount;
            $bidmapper->allowed = json_encode($request->writer_categories);
            $bidmapper->status = 1;
            $bidmapper->update();
            return redirect('order/inactivebids')->with('notice',['class'=>'success','message'=>'Order has been enabled for bidding']);
        }
        $writer_categories = WriterCategory::where('deleted','=',0)->get();
        return view('order.bids.activate',[
            'bidmapper'=>$bidmapper,
            'writer_categories'=>$writer_categories
        ]);
    }

    public function deactivateBid($id){
        $bidmapper = BidMapper::findOrFail($id);
        $bidmapper->status = 0;
        $bidmapper->update();
        return redirect('order/activebids')->with('notice',['class'=>'success','message'=>'Order disabled from bidding']);

    }

    /**
     * Show unpaid orders
     */
    public function unpaid(){
        $orders = Order::where('paid','=',0)->orderBy('created_at','asc')->paginate(10);
        return view('order.unpaid',[
           'orders'=>$orders
        ]);
    }
    /**
     * Fine writer
     */
    public function fine(Order $order,Assign $assign, Request $request){
        $method = $request->method();
        if($method=='POST'){
           $fine = $assign->fines()->create([
              'amount'=>$request->amount,
               'reason'=>$request->reason
           ]);
            $this->emailer->sendOrderFinedEmail($assign,$fine);
            return redirect("order/$assign->order_id/room/$assign->id")->with('notice',['class'=>'success','message'=>'Writer fine saved accordingly']);
        }
        return view('order.fine',[
            'assign'=>$assign,
        ]);
    }
    public function allowFile(File $file){
        $allowed = $file->allow_client;
        if($allowed){
            $file->allow_client = 0;
        }else{
            $file->allow_client = 1;
        }
        $file->update();
        echo json_encode([
            'success'=>true
        ]);
    }
    public function cancelOrder(Order $order, Assign $assign, Request $request){

        if($request->method()=='POST'){
               $fine= $assign->fines()->create([
                    'amount'=>$request->amount,
                    'reason'=>"Order Was cancelled"
                ]);
                $bidmapper = $order->bidMapper;
                if(!$bidmapper){
                    $assign->delete();
                  return redirect("order/activebids")->with('notice',['class'=>'success','message'=>'Order has been cancelled and writer fined accordingly']);
                }
                $bidmapper->status = 1;
                $bidmapper->update();
                $order->status = 0;
                $order->update();
                $assign->amount = 0;
                $assign->bonus = 0;
                $assign->rating = $request->rating;
                $assign->comments = $request->comments;
                $assign->status = $this->cancelled_status;
                $assign->update();
                $emailer = $this->emailer;
                $emailer->sendOrderCancelledEmail($assign->user,$assign,$fine,nl2br($request->comments));

            return redirect("order/activebids")->with('notice',['class'=>'success','message'=>'Order has been cancelled and writer fined accordingly']);
        }
        return view('order.cancel',[
            'order'=>$order,
            'assign'=>$assign
        ]);
    }

    /**
     * Change cost of order
     */
    public function changeCost(Request $request){
        $order_id = $request->order_id;
        $order = Order::findOrFail($order_id);
        $amount = $request->amount;
        $order->amount = $amount;
        $order->update();
        return redirect("order/$order->id")->with('notice',['class'=>'success','message'=>'Order cost updated accordingly']);
        dd($order);
    }

    /**
     * Mark order as paid
     */

    public function markPaid(Order $order,Request $request){
        $usd_amount = $request->amount;
        $reference = $request->reference;
        $order->paid = 1;
        $order->update();
        $payment = $order->paypalTxns()->create([
            'amount'=>$usd_amount,
            'txn_id'=>$reference,
            'state'=>'COMPLETED',
            'create_time'=>Carbon::now()->toDateTimeString(),
            'currency'=>'USD',
            'usd_rate'=>1
        ]);
        $payment->via = $request->via;
        $payment->update();
        if($request->isXmlHttpRequest()){
            return ['reload'=>true];
        }
        return redirect("/order/$order->id")->with('notice',['class'=>'success','message'=>'Order marked Paid']);
    }

    /**
     * Show order disputes
     */
    public function orderDisputes(){
        $disputes = Dispute::orderBy('id','desc')->where('status','=',0)->paginate(10);
        return view('order.disputes',[
           'disputes'=>$disputes
        ]);
    }

    /**
     * Resolve order dispute
     */

    public function resolveDispute(Dispute $dispute){
        $dispute->status = 1;
        $dispute->update();
        return redirect('order/disputes')->with('notice',['class'=>'success','message'=>'Dispute marked as resolved']);
    }

    public function enableTake($bidMapper_id,Request $request){
        $bidmapper = BidMapper::findOrFail($bidMapper_id);
        if($request->method()=='POST'){
            $bidmapper->allow_take = json_encode($request->writer_categories);
            $bidmapper->take_amount = number_format($request->take_amount,2);
            $bidmapper->update();
            return redirect("order/activebids")->with('notice',['class'=>'info','message'=>'Order has been enabled for take']);
        }
        $writer_categories = WriterCategory::where('deleted','=',0)->get();
        return view('order.enable_take',[
            'bidmapper'=>$bidmapper,
            'writer_categories'=>$writer_categories
        ]);
    }

    public function editOrder(Order $order,Request $request){
        $method = $request->method();
        if($method=='POST'){
           $order->pages = $request->pages;
            $order->sources = $request->sources;
            $order->instructions = $order->instructions;
            $order->topic = $request->topic;
            $order->update();
            $request->session()->flash('notice',['class'=>'success','message'=>'Order Updated']);
            return redirect("order/$order->id");
        }
        $documents= Document::where('deleted','=','0')->get();
        $subjects = Subject::where('deleted','=','0')->get();
        $rates = Rate::where('deleted','=',0)->get();
        $academic_levels = Academic::where('deleted','=',0)->get();
        $styles = Style::where('deleted','=',0)->get();
        $languages = Language::where('deleted','=',0)->get();
        $website = $this->webRepo->getWebsite();
        $writer_categories = WriterCategory::where('deleted','=',0)->orderBy('amount','asc')->get();
        $currencies = Currency::where('deleted','=',0)->get();
        $settings = ['documents'=>$documents,'styles'=>$styles,'languages'=>$languages,'subjects'=>$subjects,'rates'=>$rates,'academics'=>$academic_levels,'writer_categories'=>$writer_categories,'currencies'=>$currencies];

        return view('order.create',[
            'documents'=>$documents,
            'subjects'=>$subjects,
            'academic_levels'=>$academic_levels,
            'settings'=>$settings,
            'styles'=>$styles,
            'languages'=>$languages,
            'website'=>$website,
            'writer_categories'=>$writer_categories,
            'currencies'=>$currencies,
            'order'=>$order
        ]);
    }
    public function addPages(Order $order,Request $request){
        $pages = $request->pages;
        $order->pages = $order->pages+=$pages;
        $repo = new OrderRepository();
        $cost = $repo->calculateCost($order);
        $order->amount = $cost;
        $order->update();
        return redirect("order/$order->id")->with('notice',['class'=>'info','message'=>'More pages added, please pay the pending amount']);
    }

    public function addInstructions(Order $order,Request $request){
        $instructions = $request->instructions;
        $order->instructions = $instructions;
        $order->update();
        return redirect("order/$order->id")->with('notice',['class'=>'info','message'=>'More instructions added!']);
    }

    public function addSources(Order $order,Request $request){
        $sources = $request->sources;
        $order->sources+= $sources;
        $order->update();
        return redirect("order/$order->id")->with('notice',['class'=>'info','message'=>'More Sources added!']);
    }

    public function addHours(Order $order,Request $request){
        $hours = $request->hours;
//        dd($hours);
        $deadline = Carbon::createFromTimestamp(strtotime($order->deadline));
        $deadline->addHours($hours);
        $order->deadline = $deadline;
        $order->update();
        return redirect("order/$order->id")->with('notice',['class'=>'info','message'=>'Deadline Extended!']);
    }

    public function deleteOrder(Order $order){
       $order->messages()->delete();
        $order->assigns()->delete();
        $order->bidMapper()->delete();
        $order->disputes()->delete();
        $order->payments()->delete();
        $order->delete();
        return redirect("order/activebids")->with('notice',['class'=>'info','message'=>'order deleted']);
    }

    public function changeProgress(Assign $assign, Request $request){
        $assign->progress->update([
            'percent'=>round($request->progress,2)
        ]);

        return $assign->progress;
    }
    public function getPartialIds(){
            $partial_pending = Order::where([
            ['paid','=',1],
            ['partial','=',1]
        ])->get();
       $incomplete = [];
       $complete = [];
       foreach($partial_pending as $pending){
           $paid =  $pending->paypalTxns()->sum('amount');
           if($pending->amount<=$paid){
            $complete[] = $pending->id;
           }else{
            $incomplete[] = $pending->id;
           }
       }
       return (object)['complete'=>$complete,'pending'=>$incomplete];
    }


    public function partialPending(){
       
        $partial_pending = Order::whereIn('id',$this->getPartialIds()->pending)->paginate(10);
        return view('order.partial_orders',[
            'orders'=>$partial_pending,
            'status'=>'Pending'
        ]);
    }

    public function partialPaid(){
        $partial_pending = Order::whereIn('id',$this->getPartialIds()->complete)->paginate(10);
        return view('order.partial_orders',[
            'orders'=>$partial_pending,
            'status'=>'Paid'
        ]);
    }

    public function approveOrder(Order $order, Request $request){
        if($request->method() =='PUT'){
            $assigns = $order->assigns()->where('status','=',4)->get();
            if(count($assigns)){
                $assign = $assigns[0];
                $assign->rating = $request->rating;
                if(!$request->rating){
                    $assign->rating = 4;
                }
                $assign->comments = $request->comments;
                $assign->update();
                $order->status = 6;
                $order->update();
                return redirect("order/completed");
            }
        }
        return view('order.approve',[
            'order'=>$order
        ]);
    }

    public function completeMilestone(ProgressiveMilestone $milestone){
        $milestone->status = 1;
        $milestone->update();
        $this->emailer->sendEmailNote($milestone->order->user,"Milestone Completion Notification","Hello<br/>One of your order#".$milestone->order_id." milestones has been marked as complete. Kindly login to your account and confirm");
        return ['reload'=>true];
    }

    public function reviseMilestone(Assign $assign, ProgressiveMilestone $milestone){
        $milestone->status = 0;
        $milestone->update();
        $this->emailer->sendEmailNote($assign->user,'Milestone Returned to Revision',"Hello ".$assign->user->name."<br/> One milestone for Order #".$milestone->order->id." Has been returned to revision, please check");
        return ['reload'=>true];
    }

    public function submitQuote(Order $order,Request $request){
        $amount = round($request->amount,2);
        if($amount<1){
            return ['Invalid order amount'];
        }
        $order->amount = $amount;
        $order->partial = $request->partial;
        $order->currency_id = $request->currency_id;
        $order->update();
        $converted = $order->amount*$order->currency->usd_rate;
        $this->emailer->sendEmailNote($order->user,'Order Amount Set','Hello '.$order->user->name.'<br/>
        Your order '.$order->id.' has been confirmed by admin and order amount set to '.number_format($converted,2).' '.$order->currency->abbrev.' Kindly proceed to pay for the order then our writers will work on it and deliver 
         within the specified deadline.
         Thank you
        ');
        return ['reload'=>true];

    }

    public function checkNumber(Request $request){
        if(!Order::find($request->id)){
            return 0;
        }else{
            return 'exists';
        }
    }

    public function articles(Request $request){
        $tab = $request->tab;
        if(!$tab){
            $tab = 'published';
        }
        if($tab == 'published'){
            $published=Article::where('status',2)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $articles = $published;
        }
        if($tab == 'pending'){
            $pending=Article::where('status',1)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $articles = $pending;
        }
        return view('order.articles.index',[
            'tab'=>$tab,
            'articles'=>$articles
        ]);
    }

    public function viewArticle(Article $article){
        return view('order.articles.index',[
            'article'=>$article,
            'tab'=>'view'
        ]);
    }

    public function publishArticle(Article $article,Request $request){

        $websites = $request->websites;
        //post date is the deadline due to datetime plugin manenos
        $deadline = $request->deadline;
        if($websites == null){
            return ['please select at least one website'];
        }
        foreach($websites as $website_id) {
            if ($article->publishes()->where('post_website_id', $website_id)->first() == null) {

                $web = PostWebsite::find($website_id);
                $url = $web->url.'/wp-json/papersell/v1/';
                $client = new \GuzzleHttp\Client(['base_uri'=>$url]);
                $response = $client->request('POST','new-paper',['form_params'=>$article->toArray()]);
                $post = \GuzzleHttp\json_decode((string)$response->getBody());
                $post_id = $post->ID;
                if($post_id){
                    $article->status = 2;
                    $article->update();
                    $link = $post->guid;
                    $article_data = [
                        'post_id' => $post_id,
                        'post_website_id' => $website_id,
                        'link' => $link,
                        'article_id' => $article->id
                    ];
                    $published_article = PublishedArticle::updateOrCreate($article_data, $article_data);
                }else{
                    return redirect()->back()->with('notice',['class'=>'danger','message'=>'Post not published! kindly install publish plugin']);
                }

                    }

            }
        return ['reload' => true];
    }

    public function get_post_by_title($page_title, $post_content,$wp_load) {
        include $wp_load;
    global $wpdb;
    // $page_title = trim(preg_replace('/\s\s+/', ' ', $page_title));
    // $page_title = trim(preg_replace('/\s+/', ' ', $page_title));
//    $post_content = trim(preg_replace('/\s\s+/', ' ', $post_content));
//    $post_content = trim(preg_replace('/\s+/', ' ', $post_content));
    $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post' AND post_content = %s", $page_title,$post_content ));
    if ( $post )
        return get_post($post, OBJECT)->ID;
    return null;
    }

    public function editArticle(Article $article){
        return view('order.articles.tabs.edit',[
            'article'=>$article
        ]);
    }

    public function downloadOrderFiles(Request $request,Order $order){
        $files = File::where('order_id',$order->id);
        if($request->assign_id)
            $files = $files->orWhere('assign_id',$request->assign_id);
        $files = $files->get();
        if(count($files) == 0)
            return redirect()->back();
        $zip = new ZipArchive();
        $zip->open("Order".$order->id."_files.zip",ZipArchive::CREATE);
        foreach($files as $file){
            $path = storage_path("app/$file->path");
            $zip->addFromString($file->id.'_'.$file->filename,file_get_contents($path));
        }
        $zip->close();
        return response()->download($zip->filename)->deleteFileAfterSend($zip->filename);
    }

    public function updateArticle(Article $article,Request $request){
        $article->update($request->all());
//        foreach($article->publishes as $publish){
//            $post_id = $publish->post_id;
//            $path = $publish->website->path;
//            $wp_loadp = $path . '/wp-load.php';
//            $wp_loadp = str_replace('//', '/', $wp_loadp);
//            try{
//                include $wp_loadp;
//            $post_arr = array(
//                'ID'=>$post_id,
//                'post_title' => $article->title,
//                'post_author' => 1,
//                'post_content' => $article->content
//            );
//                wp_insert_post($post_arr);
//            }catch(\Exception $e){
//                wp_insert_post($post_arr);
//            }
//        }
        return redirect("order/articles/$article->id")->with('notice',['class'=>'success','message'=>'Article saved']);
    }

    public function deleteArticle(Article $article){
        foreach($article->publishes as $publish){
            $post_id = $publish->post_id;
            $path = $publish->website->path;
            $wp_loadp = $path . '/wp-load.php';
            $wp_loadp = str_replace('//', '/', $wp_loadp);
            try{
                include $wp_loadp;
                wp_delete_post($post_id);
            }catch(\Exception $e){

            }
            $publish->delete();
        }

        $article->delete();
        return ['reload'=>true];
    }

    public function checkSimilarity(Article $article){
        $artiles = Article::whereNotIn('id',[$article->id])->where('status',2)->get();
        $similar = [];
        $table = '<table class="table">';
        $table.='<tr><th>Article ID</th><th>Similarity</th><th>Action</th></tr>';
        foreach($artiles as $artile){
            $content = $artile->content;
            similar_text(strip_tags($content),strip_tags($article->content),$percent);
            $percent = round($percent,2);
            if($percent<30){
                $cls = 'info';
            }elseif($percent<60){
                $cls = 'warning';
            }else{
                $cls = 'danger';
            }
            $table.='<tr><td>'.$artile->id.'</td><td><div class="progress">
  <div class="progress-bar progress-bar-'.$cls.'" role="progressbar" aria-valuenow="'.$percent.'"
  aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%;color:black;">
    '.$percent.'%
  </div>
</div>
    '.$percent.'%
</td>
<td><a href="'.URL::to('order/articles/'.$artile->id).'" class="btn btn-info btn-xs">View</a></td>
</tr>';
        }
        $table.='</table>';
        return $table;
    }

    public function archived(){
        $orders = Order::where('status',$this->archived_status)->paginate(10);
        return view('order.archived',[
            'orders'=>$orders
        ]);
    }

    public function archiveOrder(Order $order){
        $order->status = 8;
        $bid_mapper = $order->bidMapper;
        $order->save();
        if($bid_mapper){
            $bid_mapper->status = 8;
            $bid_mapper->save();
        }
       return redirect()->back()->with('notice',['class'=>'success','message'=>'Order '.$order->id.' Archived']);
    }
    public function restoreArchived(Order $order){
        $order->status = 0;
        $bid_mapper = $order->bidMapper;
        $order->save();
        if($bid_mapper){
            $bid_mapper->status = 1;
            $bid_mapper->save();
        }
       return redirect()->back()->with('notice',['class'=>'success','message'=>'Order '.$order->id.' Archived']);
    }
}
