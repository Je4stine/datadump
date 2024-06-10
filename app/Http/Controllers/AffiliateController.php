<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\GmailContact;
use App\Order;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AffiliateController extends Controller
{
    //
    protected $folder = 'client.affiliate.';
    public function __construct(Request $request)
    {
        $menu_repo = new MenuRepository($request);
        $menu_repo->check();
    }

    public function gmail(){
        $gmail_contacts =  Auth::user()->gmailContacts()->where([
            ['invited','=',1],
            ['registered','=',0]
        ])->paginate(6);
        $tab = 'gmail';
        return view($this->folder.'index',[
            'tab'=>$tab,
            'gmail_contacts'=>$gmail_contacts
        ]);
    }

    public function saveInvites(Request $request){
        $emailer = new EmailRepository();
        $contacts = $request->contacts;
        $myInvites = Auth::user()->gmailContacts()
            ->whereIn('id',$contacts)
            ->get();
        foreach($myInvites as $contact){
            $emailer->sendGmailInvite($contact);
            $contact->invited = 1;
            $contact->update();
        }
        return redirect("stud/affiliate")->with('notice',['class'=>'success','message'=>'Contacts have been successfully invited']);

    }

    public function addEmail(Request $request){
        $exists = GmailContact::where('email',$request->email)->count();
        if(!$exists)
            $exists = User::where('email',$request->email)->count();
        if(!$exists){
            $emailer = new EmailRepository();
            $gmail_contact = new GmailContact();
            $gmail_contact->user_id = Auth::user()->id;
            $gmail_contact->name = $request->name;
            $gmail_contact->email = $request->email;
            $gmail_contact->status = 1;
            $gmail_contact->save();
           $emailer->sendGmailInvite($gmail_contact);
            $gmail_contact->invited = 1;
            $gmail_contact->save();
        }else{
            echo "Unable to invite ".$request->email;exit;
        }
        return ['reload'=>true];
    }

    public function index(Request $request){
        $tab = 'how';
        $gmail_contacts = null;
        if(isset($request->action)){
            $gmail_contacts = Auth::user()->gmailContacts()
                ->where([
                    ['status','=',0],
                    ['updated_at','>',Carbon::now()->subMinutes(15)->toDateTimeString()]
                ])->get()->toArray();
                $count = count($gmail_contacts);
                $max = (int)$count/2;
            $gmail_contacts = array_chunk($gmail_contacts,$max+1);
        }
        return view($this->folder.'index',[
            'tab'=>$tab,
            'gmail_contacts'=>$gmail_contacts
        ]);
    }

    public function earnings(){
        $tab = 'earnings';
        $user = Auth::user();
        $earnings = $user->affiliates()
            ->join('users','users.id','=','affiliates.user_id')
            ->join('orders','orders.id','=','affiliates.order_id')
            ->select('affiliates.*','users.name')
            ->paginate(10);
//        $others =
        return view($this->folder.'index',[
            'tab'=>$tab,
            'earnings'=>$earnings
        ]);
    }

    public function support(){
        $tab = 'support';
        return view($this->folder.'index',[
            'tab'=>$tab
        ]);
    }

    public function adminInvites(){
        $gmail_invites = GmailContact::where('invited',1)->orderBy('registered','desc')->paginate(10);
        return view('order.affiliates.invites',[
            'gmail_contacts'=>$gmail_invites
        ]);
    }

    public function adminEarnings(){
        $earnings = Affiliate::orderBy('id','desc')
            ->join('users','users.id','=','affiliates.user_id')
            ->join('orders','orders.id','=','affiliates.order_id')
            ->select('affiliates.*','users.name')
            ->paginate(10);
        return view('order.affiliates.earnings',[
            'earnings'=>$earnings
        ]);
    }
    public function awardReferrer(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            echo "User does not exist";
            exit;
        }

        $amount = $request->amount;
        $order = Order::find($request->order_id);
        if(!$order){
            echo "Order Does not exist";exit;
        }

        $user->affiliates()->create([
            'earning'=>$amount,
            'order_id'=>$order->id,
            'order_amount'=>$order->amount
        ]);
        $referrer  = $user;
        $email_repo = new EmailRepository();
        $email_repo->sendGeneralEmail('award_invite','Congratulations '.$referrer->name,$referrer);

        return ['reload'=>true];
    }
    public function reInvite($id){
        $gmail_invite = GmailContact::findOrFail($id);
        $emailer = new EmailRepository();
        $emailer->sendGmailInvite($gmail_invite);
        $gmail_invite->invited = 1;
        $gmail_invite->update();
    }

}
