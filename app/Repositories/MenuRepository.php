<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/02/28
 * Time: 2:25 PM
 */

namespace App\Repositories;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use View;
use App\Website;
use URL;

class MenuRepository
{
    protected $menus;
    protected $user;
    protected $path;
    protected $allowed;
    protected $view_menus;
    public function __construct(Request $request)
    {
        $this->path = $request->path();
        $this->user = Auth::user();
        $menus = @json_decode(Storage::disk('local')->get('/system_files/roles.json'));
        $this->menus = $menus;
        if(@$this->user->role=='admin'){
            $this->getAdminMenus();
        }else{
            $this->setAllowed();
        }
        $this->check();
        if(!$this->user){
            $role = 'guest';
        }else{
            $role = $this->user->role;
        }

        $navbar = $menus->main->$role;
        $web_repo = new WebsiteRepository();
        $website = $web_repo->getWebsite();
        View::share('navbar_menu',$navbar);
        View::share('menus',$this->view_menus);
        View::share('www',$website);
        View::share('website',$website);
    }
    public function setAllowed(){
        $user = $this->user;
        $allowed = array();
        if($user) {
            $role = $user->role;
            $menus = @$this->menus->$role;

        }else{
            $menus = @$this->menus->guest;

        }

        if(!$menus){
            Redirect::to('/')->send();
        }
        $this->view_menus = $menus;
        foreach($menus as $menu){
            $type = $menu->type;
            if($type=='single' && (!in_array($menu->menus->url,$allowed))){
                if($menu->menus->url){
                    $allowed[]=$menu->menus->url;
                }
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }
            }
            if($type=='hidden'){
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }

            }
            if($type=='many'){
                foreach($menu->menus as $drop){
                    if(!in_array($drop->url,$allowed)){
                        if($drop->url){
                            $allowed[]=$drop->url;
                        }
                    }
                }
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }
            }
        }
        foreach($this->menus->guest as $menu){
            $type = $menu->type;
            if($type=='single' && (!in_array($menu->menus->url,$allowed))){
                if($menu->menus->url){
                    $allowed[]=$menu->menus->url;
                }
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }
            }
            if($type=='hidden' && (!in_array($menu->menus->url,$allowed))){
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }

            }

        }

        $this->allowed = $allowed;
    }
    public function check(){
        $path = $this->path;
        $allowed = $this->allowed;
        $path_array = explode('/',$path);
        $real_path  = array();
        foreach($path_array as $value){
            if(!is_numeric($value)){
                $real_path[]=$value;
            }
        }
        $path = implode('/',$real_path);
        $user = $this->user;
        if(isset($this->user)){
            if($this->user->role=='writer' && @$user->profile->step<5 && $path != 'user/complete_profile' && $path != 'user/profile' && $path != 'logout'){
                Redirect::to('user/complete_profile')->with('notice',['class'=>'info','message'=>'Please complete your profile details'])->send();
                die();
            }
        }


        if(!in_array($path,$allowed)){
            if(!$this->user){
                Redirect::to('/login')->send();
            }
            if($this->user->role=='admin'){
                foreach($allowed as $url){
                    if($url=='order'){
                        Redirect::to('/order')->send();
                    }
                }
                Redirect::to('/stud/new')->send();
            }
            if($this->user->role=='writer'){
                Redirect::to('/writer')->send();
            }
            if($this->user->role=='client'){
                Redirect::to('/stud')->send();
            }
            Redirect::to('/')->with('notice',['class'=>'error','message'=>'You are not allowed to view this order!'])->send();
            die();
        }
    }

    public function getAdminMenus(){
        $user = $this->user;
        $user_group = $this->user->adminGroup;
        $allowed_slugs = json_decode($user_group->permissions);
        $role = $user->role;
        $allowed = array();
            $menus = @$this->menus->$role;
        $allowed_menus = [];
        foreach($menus as $menu){
            if(in_array($menu->slug,$allowed_slugs)) {
                $allowed_menus[]=$menu;
                $type = $menu->type;
                if ($type == 'single' && (!in_array($menu->menus->url, $allowed))) {
                    if ($menu->menus->url) {
                        $allowed[] = $menu->menus->url;
                    }
                    foreach ($menu->urls as $url) {
                        if (!in_array($url->url, $allowed)) {
                            if ($url->url) {
                                $allowed[] = $url->url;
                            }
                        }
                    }
                }
                if ($type == 'hidden') {
                    foreach ($menu->urls as $url) {
                        if (!in_array($url->url, $allowed)) {
                            if ($url->url) {
                                $allowed[] = $url->url;
                            }
                        }
                    }


                }
                if ($type == 'many') {
                    foreach ($menu->menus as $drop) {
                        if (!in_array($drop->url, $allowed)) {
                            if ($drop->url) {
                                $allowed[] = $drop->url;
                            }
                        }
                    }
                    foreach ($menu->urls as $url) {
                        if (!in_array($url->url, $allowed)) {
                            if ($url->url) {
                                $allowed[] = $url->url;
                            }
                        }
                    }
                }
            }
        }
        foreach($this->menus->guest as $menu){
            $type = $menu->type;
            if($type=='single' && (!in_array($menu->menus->url,$allowed))){
                if($menu->menus->url){
                    $allowed[]=$menu->menus->url;
                }
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }
            }
            if($type=='hidden' && (!in_array($menu->menus->url,$allowed))){
                foreach($menu->urls as $url){
                    if(!in_array($url->url,$allowed)){
                        if($url->url){
                            $allowed[]=$url->url;
                        }
                    }
                }

            }
            if ($type == 'many') {
                foreach ($menu->menus as $drop) {
                    if (!in_array($drop->url, $allowed)) {
                        if ($drop->url) {
                            $allowed[] = $drop->url;
                        }
                    }
                }
                foreach ($menu->urls as $url) {
                    if (!in_array($url->url, $allowed)) {
                        if ($url->url) {
                            $allowed[] = $url->url;
                        }
                    }
                }
            }

        }
        $this->allowed = $allowed;
        $this->view_menus = $allowed_menus;
    }
}