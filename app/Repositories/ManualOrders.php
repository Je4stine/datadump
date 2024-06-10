<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2/21/17
 * Time: 8:40 AM
 */

namespace App\Repositories;


use App\Assign;
use App\Http\Requests\Request;
use App\Order;
use App\User;
use Auth;

class ManualOrders
{
    protected $role;
    protected $user;
    protected $order_obj;
    protected $assign_obj;
    protected $orders = [];

    protected $active_status = 0;
    protected $available_status = 0;
    protected $revision_status = 2;
    protected $active_bids = 0;
    protected $completed_status = 4;
    protected $pending_status = 3;


    public function __construct()
    {
        $user = Auth::user();
        $this->role = $user->role;
        $this->user = $user;
        if($this->role == 'admin'){
            $this->order_obj = Order::where([
                ['is_manual','=',1],
            ]);
            $this->assign_obj = Assign::where('is_manual',1);
        }else{
            $this->order_obj = Order::where([
                ['is_manual','=',1],
            ]);
            $this->assign_obj = $user->assigns()->where('is_manual',1);
        }

    }

    public function getNew(){
        $this->orders = $this->order_obj->where('status',10)->paginate(10);
        return $this->orders;
    }
    public function getActive(){
        return $this->assign_obj->where('status',$this->active_status)->paginate(10);
    }

    public function getPending(){
        return $this->assign_obj->where('status',$this->pending_status)->paginate(10);
    }

    public function getApproved(){
        return $this->assign_obj->where('status',$this->completed_status)->paginate(10);
    }

    public function getRevision(){
        return $this->assign_obj->where('status',$this->revision_status)->paginate(10);
    }

}