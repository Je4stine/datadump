<?php

namespace App\Http\Controllers;

use App\Forget;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\Repositories\WebsiteRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;

class ResetController extends Controller
{
    //
    public function __construct(MenuRepository $menuRepository){
     $menuRepository->check();
    }
    public function index(Request $request){
        if($request->method()=='POST'){
            $website_repo = new WebsiteRepository();
            $web_id = $website_repo->getWebsiteId();
            $email = $request->email;
            $user  = User::where([
                ['email','like',$email],
                ['website_id','=',$web_id]
            ])->first();
            if($user){
                $token = bin2hex(openssl_random_pseudo_bytes(45));
                $user->forgets()->create([
                   'token'=>$token,
                    'expiry'=>Carbon::now()->addDays(5)
                ]);
                $email_repo = new EmailRepository();
                $email_repo->sendResetEmail($user,$token);
                return redirect("forgot/password?class=success&message=Link has been emailed to $email, It may take upto 10mins to appear.<br/>If it does not appear try checking your spam folder and mark it as not spam")->with('notice',['class'=>'success','message'=>'Link has been mailed']);
            }else{
                return redirect("forgot/password?class=error&message=email does not exist")->with('notice',['class'=>'error','message'=>'Email does not exist']);
            }
        }
        return view('auth/passwords.email');
    }

    public function reset(Request $request){

        $token = $request->token;
        if(!$token){
            return redirect("login")->with('notice',['class'=>'error','message'=>'Invalid token']);
        }
        $website_repo = new WebsiteRepository();
        $web_id = $website_repo->getWebsiteId();
        $found = Forget::where('token','like',$token)->first();
        $exists = null;
        if($found){
            $user = $found->user;
            if($user->website_id==$web_id){
                $exists = $user;
            }
        }
        if(!$exists){
            $request->session()->flash('notice',['class'=>'error','message'=>'Invalid token']);
        }else{
            if($request->method()=='POST'){
                $this->validate($request,[
                    'email' => 'required|email|max:255',
                    'password' => 'required|confirmed|min:6',
                ]);
                $user->password = bcrypt($request->password);
                $user->update();
                Auth::login($user);
                $found->delete();
                return redirect("order/stud")->with('notice',['class'=>'success','message'=>'Password updated']);

            }
        }
        return view('auth.passwords.reset',[
            'token'=>$token
        ]);
    }
}
