<?php

namespace App\Http\Controllers;

use App\Email;
use App\Jobs\BulkSendEmails;
use App\Repositories\EmailRepository;
use App\Repositories\MenuRepository;
use App\Website;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    //
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(Request $request){
        if($request->search){
            $emails = Email::orderBy('id','desc')->where([
                ['deleted','=',0],
                ['action','like',"%$request->search%"]
            ])->paginate(10);
        }else{
            $emails = Email::orderBy('id','desc')->where('deleted','=',0)->paginate(10);
        }
        return view('settings.emails.index',[
            'emails'=>$emails
        ]);
    }

    /**
     * Add/edit a email template
     */

    public function addEmail(Request $request){
        Email::updateOrCreate([
            'id'=>$request->id
        ],[
            'action'=>$request->action,
            'description'=>$request->description
        ]);
        return redirect("settings/emails")->with('notice',['class'=>'success','message'=>'Email template added']);
    }

    /**
     * Delete an email template
     */

    public function deleteEmail(Email $email){
        $website = $email->website;
        $email->deleted = 1;
        $email->update();
        return redirect("settings/emails")->with('notice',['class'=>'success','message'=>'Email template removed']);
    }

    /**
     * save an email template design
     */

    public function saveTemplate(Email $email,Request $request){


        $website = $email->website;
        $email->template = $request->template;
        $email->update();
        return redirect("settings/emails")->with('notice',['class'=>'success','message'=>'Email template added']);
    }

    /**
     * edit a email template
     */

    public function editTemplate(Email $email){
        return view('settings.emails.template',[
            'email'=>$email
        ]);
    }

    public function sendEmails(Request $request){
        $templates = Email::get();
        return view('settings.emails.send',[
            'templates'=>$templates
        ]);
    }

    public function editEmail(Request $request){
        $email = Email::findOrNew($request->template_id);
        $q = User::where('role','like',$request->role);
        $all_users = $q->get();
        if(!$request->all_pages){
            $ids = $request->user_ids;
            $q->whereIn('id',$ids);
        }else{
            if($request->search){
                $key = $request->search;
                if(filter_var($key,FILTER_VALIDATE_EMAIL)){
                    $q->where('email','like',"%$key%");
                }else{
                    $q->where('name','like',"%$key%");
                }
            }
        }
        $users = $q->get();
        $ids = [];
        foreach($users as $user){
            $ids[]=$user->id;
        }

        return view('settings.emails.edit_email',[
            'ids'=>$ids,
            'all_users'=>$all_users,
            'email'=>$email
        ]);
    }

    public function mailUsers(Request $request){
        $ids = $request->users;
        $users = User::whereIn('id',$ids)->get();
        $message = $request->template;
        $subject = $request->subject;
        $emailRepository = new EmailRepository();
        if(count($users)){
            foreach($users as $user){
                $emailRepository->sendEmailNote($user,$subject,$message);
            }
        }
        return redirect('/')->with('notice',['class'=>'success','message'=>count($users)." Email(s) prepared and queed for later sending"]);
    }
}
