<?php

namespace App\Http\Controllers;

use App\Assurance;
use App\Repositories\FileSaverRepository;
use App\Repositories\MenuRepository;
use App\Website;
use Dropbox\Exception;
use Illuminate\Http\Request;
use URL;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    //
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Show all the websites
     */
    public function index(){
        $websites = Website::orderBy('id','desc')->paginate(5);
        return view('websites.index',[
            'websites'=>$websites
        ]);
    }

    /**
     * Save a new or edited website
     */

    public function addWebsite(Request $request){
        $website = Website::findOrNew($request->id);
        if($request->method()=='POST'){

            if($request->name){
                if(!$request->id){
                    $this->validate($request,[
                        'home_url'=>'required|unique:websites'
                    ]);
                }
                $website->name = $request->name;
                $website->home_url = $request->home_url;
                $website->role = $request->role;
                $website->layout = $request->layout;
                $website->telephone = $request->telephone;
                $website->email = $request->email;
                $website->password = $request->password;
                $website->host = $request->host;
                $website->port = $request->port;
                $website->encryption = $request->encryption;
                $website->deposit = $request->deposit;
                $website->save();
                return redirect("websites/view/$website->id")->with('notice',['class'=>'success','message'=>'Website saved!']);
            }
            if($request->points_per_referral){
                $website->points_per_referral = $request->points_per_referral;
                $website->point_pay_amount = $request->point_pay_amount;
                $website->redeem_rate = $request->redeem_rate;
                $website->update();
                return ['reload'=>true];

            }

        }
        return view('websites.add',[
            'website'=>$website
        ]);
    }

    /**
     * Edit a website
     */

    public function editWebsite(Website $website){
        return view('websites.add',[
            'website'=>$website
        ]);
    }

    /**
     * View a single website
     */

    public function viewWebsite(Website $website){
        return view('websites.view',[
            'website'=>$website
        ]);
    }

    /**
     * Upload a website logo
     */

    public function uploadLogo(Website $website, Request $request){
        $fileSaver = new FileSaverRepository();
        $resp = $fileSaver->uploadImage($request,'logo');
        if($resp['path']){
            $home = $website->home_url;
            $website->logo = $home.'/'.$resp['path'];
            $website->update();
        }
        return redirect("websites/view/$website->id")->with('notice',$resp);

    }

    /**
     * Add promo image
     */

    public function uploadPromo(Website $website, Request $request){
        $fileSaver = new FileSaverRepository();
        $resp = $fileSaver->uploadImage($request,'promo_image');
        if($resp['path']){
            $website->promo_image = $resp['path'];
            $website->update();
        }
        return redirect("websites/view/$website->id")->with('notice',$resp);

    }

    /**
     * add website punchline
     */

    public function addPunchline(Website $website,Request $request){
        $website->punchlines()->create([
            'assurance'=>$request->assurance
        ]);
        return redirect("websites/view/$website->id")->with('notice',['class'=>'success','message'=>'Punchline added']);
    }

    /**
     * delete puncline
     */
    public function deletePunchline(Assurance $assurance){
        $website = $assurance->website;
        $assurance->delete();
        return redirect("websites/view/$website->id")->with('notice',['class'=>'success','message'=>'Punchline removed']);
    }
}
