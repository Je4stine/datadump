<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Repositories\MenuRepository;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    //
    protected $user;
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
        $this->user = Auth::user();
    }

    public function announcements($role,Request $request){
        $announcements = Announcement::where('target','LIKE',$role)->orderBy('created_at','desc')->paginate(5);
        return view('announcement.view',[
            'announcements'=>$announcements,
            'role'=>$role
        ]);
    }

    public function createAnnouncement($role, Request $request){
        if($request->method()=='POST'){
            $announcement = $this->user->announcements()->create([
                        'message'=>$request->message,
                        'target'=>$role
            ]);
            return redirect("/announcements/view/$role")->with('notice',['class'=>'success','message'=>"Announcement added, $role will be able to see"]);
        }
        return view('announcement.add',[
            'role'=>$role
        ]);
    }

    public function publish(Announcement $announcement){
        $announcement->published = 1;
        $announcement->update();
        return ['reload'=>true];
    }
    public function unpublish(Announcement $announcement){
        $announcement->published = 0;
        $announcement->update();
        return ['reload'=>true];
    }
    public function delete(Announcement $announcement){
        $announcement->delete();
        return ['reload'=>true];
    }

    public function viewAnnouncement(Announcement $announcement){
        return view('announcement.view_single',[
            'announcement'=>$announcement
        ]);
    }
}
