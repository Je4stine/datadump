<?php

namespace App\Http\Controllers;

use App\AdminGroup;
use App\Repositories\MenuRepository;
use App\Repositories\WebsiteRepository;
use Illuminate\Http\Request;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
class AdminGroupController extends Controller
{
    //
    protected $folder  = 'admin_groups.';
    public function __construct(MenuRepository $menuRepository)
    {
        $menuRepository->check();
    }

    public function index(Request $request){
        if($request->search){
            $key = $request->search;
            $admin_groups = AdminGroup::where('name','like',"%$key%")->orderBy('id','desc')->paginate(10);

        }else{
            $admin_groups = AdminGroup::orderBy('id','desc')->paginate(10);
        }
        return view($this->folder.'index',[
            'admin_groups'=>$admin_groups
        ]);
    }

    public function save(Request $request){
        $group = AdminGroup::findOrNew($request->id);
        $group->permissions = json_encode($request->permissions);
        $group->name = $request->name;
        $group->description = $request->description;
        $group->save();
        return redirect("admin_groups/view/$group->id")->with('notice',['class'=>'success','message'=>'Group added successfully']);

    }

    public function add(Request $request){
        $menus = json_decode(Storage::disk('local')->get('/system_files/roles.json'))->admin;
        $group = AdminGroup::findOrNew($request->id);
        if(!$group->permissions){
            $group->permissions = '[]';
        }
        return view($this->folder.'add',[
            'admin_group'=>$group,
            'admin_menus'=>$menus
        ]);
    }

    public function view($id){
        $admin_group = AdminGroup::findOrFail($id);
        return view($this->folder.'view',[
            'admin_group'=>$admin_group
        ]);
    }

    public function addUser($id,Request $request){
        $admin_group = AdminGroup::findOrFail($id);
        $websiteRepo = new WebsiteRepository();
        $website_id = $websiteRepo->getWebsiteId();
        $exists = User::where([
            ['website_id','=',$website_id],
            ['email','=',$request->email]
        ])->get();
        if(count($exists)>0){
            return redirect("admin_groups/view/$id")->withErrors([
                'email'=>'Email has already been used'
            ])
                ->withInput();
        }
        $this->validate($request,[
            'name' => 'required|max:255',
            'phone' => 'required|max:18',
            'country' => 'required|max:50',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        $admin_group->users()->create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'phone'=>$request->phone,
            'layout'=>env('LAYOUT','gentella'),
            'country'=>$request->country,
            'website_id'=>$website_id,
            'role'=>'admin'
        ]);
        $request->session()->flash('notice',['class'=>'success','message'=>'User Added successfully']);
        return view($this->folder.'view',[
            'admin_group'=>$admin_group
        ]);
    }
}
