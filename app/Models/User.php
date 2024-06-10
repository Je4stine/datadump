<?php

namespace App;
use App\Order;
use App\File;
use App\Repositories\EmailRepository;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

//use App\
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','layout','phone','country','website_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','layout',
    ];

    protected $active_status = 0;
    protected $available_status = 0;
    protected $revision_status = 2;
    protected $active_bids = 0;
    protected $completed_status = 4;
    protected $pending_status = 3;
    protected $order_confirmed_status = 6;
    protected $cancelled_status = 7;


    /**
     * user phone numbers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phones(){
        return $this->hasMany(Phone::class);
    }
    public function forgets(){
        return $this->hasMany(Forget::class);
    }

     public function articles(){
        return $this->hasMany(Article::class);
    }
    /**
    * Get user Orders
    *
    */

    public function orders(){
        return $this->hasMany(Order::class);
    }

    /**
     * Get user bids
     *
     */
    public function bids(){
        return $this->hasMany(Bid::class,'user_id','id');
    }

    /**
     * Assigned orders
     */
    public function assigns(){
        return $this->hasMany(Assign::class);
    }
    public function getRating(){
        $id = $this->id;
        $user = $this->find($id);
        $rating = $user->assigns()->join('orders','orders.id','=','assigns.order_id')
            ->where([
                ['orders.status','=',6]
                ])
        ->avg('assigns.rating');
        return number_format($rating,2);
    }

    public function addonPoints(){
        return $this->hasMany(AddonPoint::class);
    }

    public function announcements(){
        return $this->hasMany(Announcement::class);
    }

    public function promotions(){
       return $this->hasMany(Promotion::class);
    }

    public function disputes(){
        return $this->hasMany(Dispute::class);
    }

    public function getOrderCost(Order $order){
        dd($order);
    }

    public function traits(){
        return $this->hasMany(UserTrait::class);
    }

    public function website(){
        return $this->belongsTo(Website::class);
    }


    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function accountTopUps(){
        return $this->hasMany(AccountTopUp::class);
    }

    /**
     * Check if user is allowed to resource
     */
        public function isAllowedTo($role){
         $group = Auth::User()->adminGroup;
            $permissions = json_decode($group->permissions);
           return in_array($role,$permissions);
        }

    /**
     * the following functions give writer profile details
     */
    public function active(){
        $id = $this->id;
        $writer = $this->find($id);
        $count  = $writer->assigns()->where('status','=',$this->active_status)->count();
        return $count;
    }

    public function completed(){
        $id = $this->id;
        $writer = $this->find($id);
        $count  = $writer->assigns()->where('status','=',$this->completed_status)->count();
        return $count;
    }

    public function revision(){
        $id = $this->id;
        $writer = $this->find($id);
        $count  = $writer->assigns()->where('status','=',$this->revision_status)->count();
        return $count;
    }

    public function pending(){
        $id = $this->id;
        $writer = $this->find($id);
        $count  = $writer->assigns()->where('status','=',$this->pending_status)->count();
        return $count;
    }

    public function cancelled(){
        $id = $this->id;
        $writer = $this->find($id);
        $count  = $writer->assigns()->where('status','=',$this->cancelled_status)->count();
        return $count;
    }

    public function files(){
        return $this->hasMany(File::class);
    }
    public function paymentAccounts(){
        return $this->hasMany(PaymentAccount::class);
    }

    public function suspensions(){
        return $this->hasMany(Suspension::class);
    }

    public function payments(){
        return $this->hasMany(Payout::class);
    }

    public function payouts(){
        return $this->hasMany(Payout::class,'payer_id');
    }

    public function writerCategory(){
        return $this->belongsTo(WriterCategory::class);
    }

    public function adminGroup(){
        return $this->belongsTo(AdminGroup::class);
    }

    public function devices(){
        return $this->hasMany(Device::class);
    }

    public function totalWorked($is_manual=0){
        $user = $this;
        $amount = $user->assigns()->whereIn('status',[$this->pending_status,$this->completed_status])->where('is_manual',$is_manual)->sum('amount');
        $bonus = $user->assigns()->whereIn('status',[$this->pending_status,$this->completed_status])->where('is_manual',$is_manual)->sum('bonus');
        $fines = $user->assigns()->whereIn('status',[$this->pending_status,$this->completed_status])->where('is_manual',$is_manual)->sum('fine');
        $total = $amount+$bonus;
        $total-=$fines;
        return $total;
    }

    public function totalWithdrawn($is_manual = 0){
        $user = User::find($this->id);
        $total =  $user->payments()->where('is_manual',$is_manual)->sum('amount');
        return $total;
    }

    function totalPending($is_manual=0){
        $user = User::find($this->id);
        $amount = $user->assigns()
            ->join('orders','orders.id','=','assigns.order_id')
            ->where('assigns.is_manual',$is_manual)
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status])
            ->whereNotIn('orders.status',[6])
            ->sum('assigns.amount');
        $bonus = $user->assigns()
            ->join('orders','orders.id','=','assigns.order_id')
            ->where('assigns.is_manual',$is_manual)
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status])
            ->whereNotIn('orders.status',[6])
            ->sum('assigns.bonus');
        $fines = $user->assigns()
            ->join('orders','orders.id','=','assigns.order_id')
            ->where('assigns.is_manual',$is_manual)
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status])
            ->whereNotIn('orders.status',[6])
            ->sum('assigns.fine');
        $total = $amount+$bonus;
        $total-=$fines;
//        $total = round($total,2);
        return $total;
    }

    function totalFines($is_manual=0){
        $user = $this;
        $fines = $user->assigns()
            ->join('fines','fines.assign_id','=','assigns.id')
            ->where('is_manual',$is_manual)
            ->whereIn('assigns.status',[$this->pending_status,$this->completed_status,$this->cancelled_status])
            ->sum('fines.amount');
        return $fines;
    }
    function awardReferrer(Paypaltxn $payment){
        $user = $this;
        $referrer = null;
        $gmail_contant = null;
        if($user){
                $referrer = User::find($user->referred_by);
                if(!$referrer){
                    $gmail_contant = @GmailContact::where('email',$user->email)->first();
                    if($gmail_contant){
                        $referrer = $gmail_contant->user;
                        $user->referred_by = $referrer->id;
                        $user->update();
                    }
                    
                }
                if($referrer){
                    $website = $referrer->website;
                    $earning =($website->referral_commission/100) * $payment->amount;
                    $referrer->affiliates()->create([
                        'earning'=>$earning,
                        'order_id'=>$payment->id,
                        'order_amount'=>$payment->amount
                    ]);
                    if($gmail_contant){
                        $gmail_contant->registered = 1;
                        $gmail_contant->save();
//                        $referrer->referred_by =
                    }
                    $order = $payment->order;
                    $order->referred_by = $referrer->id;
                    $order->earnings = $earning;
                    $order->update();
                    $email_repo = new EmailRepository();
                    $email_repo->sendGeneralEmail('award_invite','Congratulations '.$referrer->name,$referrer);
            }
        }
    }
    public function getBalance(){
        $user = $this;
        $acc_top_ups = $this->accountTopUps()->sum('amount');
        $used_balance = $user->orders()->join('paypaltxns','orders.id', '=','paypaltxns.order_id')
            ->where([
                ['via','like','account_pay']
            ])->sum('paypaltxns.amount');
        // $redeemed_amount = $this->redeemedArticles()->sum('amount');
        // $acc_top_ups+=$redeemed_amount;
        $affiliate_earnings = $this->affiliates()->sum('earning');
        $acc_top_ups+=$affiliate_earnings;
        $remaining = $acc_top_ups-$used_balance;
        return $remaining;
    }

    public function getPoints(){
        $user = $this;
        $website = $user->website;
        $total_order_payments = $user->orders()->join('paypaltxns','orders.id', '=','paypaltxns.order_id')
            ->sum('paypaltxns.amount');
        $point_pay_amount = $website->getPointPay();
        $payment_points = $total_order_payments/$point_pay_amount;
        $redeemed = $this->accountTopUps()->sum('redeemed_points');
        $referral_points = User::where('referred_by',$this->id)->sum('referral_points');
        $addon_points = $this->addonPoints()->sum('points');
        // $author_points = $this->getAuthorPoints();
        $total_points = ($payment_points+$referral_points+$addon_points)-$redeemed;
        if($total_points<0){
            $total_points = 0;
        }
        return round($total_points,0);
    }



    function redeemedArticles(){
        return $this->hasMany(RedeemedArticles::class);
    }

    function getUnredeemedArticles(){
        $all = $this->articles()->where([
            ['author_type','=',2],
            ['status','=',2]
        ])->count();
        $redeemed_articles = $this->redeemedArticles()->sum('articles_count');
        return $all-$redeemed_articles;
    }

    function affiliates(){
        return $this->hasMany(Affiliate::class);
    }

    function gmailContacts(){
        return $this->hasMany(GmailContact::class);
    }

    public function hasPendingTest(){
        if($this->status == 0){
            if($this->test_done == 0){
                $test = $this->writerTest;
                if(!$test){
                    $tests = RandomTest::where('active',1)->get();
                    $max = count($tests)-1;
                    $random_test = $tests[random_int(0,$max)];
                    $test = $this->writerTest()->create([
                        'random_test_id'=>$random_test->id
                    ]);
                }
                return $test;
            }else{
                return false;
            }
        }
        return false;
    }

    public function writerTest(){
        return $this->hasOne(WriterTest::class);
    }

    public function getFormattedPhone(){
        $country = $this->country;
        $phone = $this->phone;
        $phone = 'tt'.$phone;
        $phone = str_replace('tt0','',$phone);
        $phone = str_replace('tt','',$phone);
        $phone = str_replace('-','',$phone);
        $phone = str_replace(' ','',$phone);
        $country_array = explode('(',$country);

        if(count($country_array)>1){
            $country_code = explode(')',$country_array[1])[0];
        }else{
            $country_code = '';
        }
        $phone = str_replace($country_code, '', $phone);
        return $country_code.$phone;
    }
}
