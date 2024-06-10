<?php

namespace App\Http\Controllers;

use App\Order;
use App\Repositories\MenuRepository;
use App\Website;
use Illuminate\Http\Request;

use App\Http\Requests;

class ReferralController extends Controller
{
    //
    protected $folder = "referrals.";

    public function __construct(Request $request)
    {
        $menu_repo = new MenuRepository($request);
        $menu_repo->check();
    }

    public function index(Request $request){
        $tab = $request->tab;
        $earnins = null;
        $websites = null;
        if($tab == '' || $tab == 'earnings'){
            $earnins = Order::join('users','users.id','=','orders.referred_by')
                ->select('orders.*','users.id as referrer_id','users.email')
                ->paginate(10);
        }
        if($tab == 'config'){
            $websites = Website::get();
        }
        return view($this->folder.'index',[
            'earnings'=>$earnins,
            'websites'=>$websites
        ]);
    }

    public function updateCommission(Request $request){
        $website = Website::findOrFail($request->id);
        $website->referral_commission = $request->referral_commission;
        $website->update();
        return ['reload'=>true];
    }
}
