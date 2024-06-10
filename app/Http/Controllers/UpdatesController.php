<?php

namespace App\Http\Controllers;

use App\Repositories\MenuRepository;
use Dropbox\Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;
class UpdatesController extends Controller
{
    //
    protected $folder='updates.';
    public function __construct(Request $request)
    {
        $menu = new MenuRepository($request);
    }

    public function index(){
        return view($this->folder.'index');
    }

    public function updateNow(Request $request){
        $zip = new \ZipArchive();
        $file = $request->file('zip');
        $zip->open($file->getRealPath());
        $section = explode('.',$file->getClientOriginalName())[0];
        $base_path = base_path();
        $path = null;
        if($section=='views'){
            $path = $base_path.'/resources';
            $zip->deleteName('layouts/mail.blade.php');
        }elseif($section=='app'){
            $path = $base_path;
        }elseif($section == 'controllers' || $section == 'routes'){
            $path = $base_path.'/app/Http';
        }elseif($section=='repositories'){
            $path = $base_path.'/app';
        }elseif($section=='vendor'){
            $path = $base_path;
        }
        try{
            $zip->extractTo($path);
            return redirect("updates")->with('notice',['class'=>'success','message'=>$section.' Updated successfully']);
        }catch (Exception $e){
        }

    }
}
