<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/02/26
 * Time: 8:17 PM
 */

namespace App\Repositories;


use App\Assign;
use App\File;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileSaverRepository
{
    public $paths;
    public $path;
    protected $assign;
    protected $for;
    protected $user;
    public $uploaded;
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function saveAssignFiles(Request $request,$assign=null,$for="Revision"){
        $this->for = $for;
        $this->assign = $assign;
        $files = $request->files;
        foreach($files as $single){
            if(is_array($single)){
                foreach($single as $file){
                    $this->paths[] = $this->uploadFile($file);
                }
            }else{
                $this->paths[] = $this->uploadFile($single);
            }
        }
        return $this->paths;

    }
    public function uploadFile($file,$save=null){
        $path = null;
        if($file){
            $real_path = $file->getRealPath();
            $filename = $file->getClientOriginalName();
            $size = $file->getClientSize();
            $file_type = explode('.', $filename)[1];
            $year = date('Y');
            $month = date('M');
            $day = date('d');
            $filename = str_replace("#", '_', $filename);
            $filename = str_replace(" ", "_", $filename);
            $path = 'uploads/' . $year . '/' . $month . '/' . $day . '/' . strtotime(date('h:i:s')) . '_' . $filename;
            Storage::put(
                $path,
                file_get_contents($real_path)
            );
            if(isset($this->assign)){
               $file= $this->assign->files()->create([
                    'user_id' => $this->user->id,
                    'filesize' => $size,
                    'filename' => $filename,
                    'file_type' => $file_type,
                    'file_for' => $this->for,
                    'path' => $path
                ]);
                $this->uploaded[] = $file;
            }else{
                if($save != null){
                    if($this->user != null){
                        $file= $this->user->files()->create([
                            'user_id' => $this->user->id,
                            'filesize' => $size,
                            'filename' => $filename,
                            'file_type' => $file_type,
                            'file_for' => 'profile',
                            'path' => $path
                        ]);
                    }else{
                        $file = File::create([
//                            'user_id' => $this->user->id,
                            'filesize' => $size,
                            'filename' => $filename,
                            'file_type' => $file_type,
                            'file_for' => 'profile',
                            'path' => $path
                        ]);
                    }

                    $this->uploaded[] = $file;
                    $path = $file;
                }else{
                    $path = [
                        'size'=>$size,
                        'filename'=>$filename,
                        'filesize' => $size,
                        'file_type'=>$file_type,
                        'path'=>$path
                    ];
                }

            }
            return $path;
        }

    }
    public function submitAssignFiles(Assign $assign){
        //silence is golden
    }

    public function uploadOrderFiles(Order $order, Request $request,$for="Order File"){
        $files = $request->files;
//        dd($files);
        foreach($files as $single){
            if(is_array($single)){
                foreach($single as $file){
                    if($file != null){
                        $details = $this->uploadFile($file);
                        $details['file_for']=$for;
                        $details['user_id']=Auth::user()->id;
                        $details['allow_client']=1;
                        $file =  $order->files()->create($details);
                    }

                }
            }else{
                if(!$file != null){
                    $details = $this->uploadFile($single);
                    $details['file_for']=$for;
                    $details['user_id']=Auth::user()->id;
                    $details['allow_client']=1;
                    $file =  $order->files()->create($details);
                }

            }

        }
    }

    public function uploadImage(Request $request, $form_name = "image"){
        if(getimagesize($request->file($form_name)->getRealPath())){
            $file = $request->file($form_name);
            $real_path = @$file->getRealPath();
            $filename = $file->getClientOriginalName();
            $year = date('Y');
            $month = date('M');
            $day = date('d');
            $relative_path = '/uploads/'.$year.'/'.$month.'/'.$day;
            $directory =public_path().$relative_path;
            $new_name = strtotime(date('h:i:s')).'_' .str_replace(' ','_',$filename);
            $path = $relative_path.'/'.$new_name;
            if($request->file($form_name)->move($directory,$new_name)){
                $status = [
                    'class'=>'success',
                    'message'=>'Image uploaded successfully',
                    'path'=>$path
                ];
            }else{
                $status = [
                    'class'=>'error',
                    'message'=>'Unexpected error occurred while moving image',
                    'path'=>''
                ];
            }
        }else{
            $status = [
                'class'=>'error',
                'message'=>'Invalid image specified',
                'path'=>null
            ];
        }
        return $status;
    }

    public function addOrderFiles(){

    }
}