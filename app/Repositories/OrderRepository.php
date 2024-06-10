<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/01/31
 * Time: 5:11 PM
 */

namespace App\Repositories;


use App\AdditionalFeature;
use App\Assign;
use App\Bid;
use App\File;
use App\Subject;
use App\Urgency;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Order;
use App\User;
use Carbon\Carbon;
use App\BidMapper;

class OrderRepository
{
    protected $assign_completed = 4;

    /**
     * Get orders for a user
     */
    public function getForUser(User $user,$status=0){
        return Order::where([
                         ['user_id',$user->id],
                         ['status',$status]
                        ])
                      ->orderBy('id','desc')
                      ->paginate(15);
    }

    /**
     * Get a single order for user
     * @param User $user
     * @param $id
     * @return mixed
     */
    public function getOneUserOrder(User $user,$id){
        return Order::where([
            ['user_id',$user->id],
            ['id',$id]
        ])
            ->get();
    }

    /**
     * Get order files
     */

    public function getOrderFiles($id){
        return File::where('order_id',$id)
                ->paginate(5);
    }

    /**
     * Public function get all available orders
     */

    public function getAvailable(){
        return Order::where('status',0)
                ->paginate(10);
    }

    /**
     * Assign order from bid
     */
    public function assignBid(Bid $bid, Request $request, Order $order){
        $assign = $order->assigns()->create([
            'deadline'=>$request->deadline,
            'fine'=>'0.00',
            'user_id'=>$bid->user_id,
            'amount'=>$bid->amount,
            'bonus'=>$request->bonus,
        ]);
        $order->save();
        return $assign;
    }

    /**
     * Get active orders for a writer
     */
    public function getWriterActive(User $user){
        return Assign::where([
            ['user_id',$user->id],
            ['status',0]
        ])
            ->orderBy('deadline','asc')
            ->paginate(15);
    }
    public function autoApprove(){
        $date = Carbon::now();
        $expiry = $date->addDays(1)->toDateString();
        $found = [];
       $unrated = Order::where('status','=',4)->get();


        foreach($unrated as $order){
            $item = $order->assigns()->where('status','=',4)->first();
        if(isset($item)){           
            $deadline = Carbon::createFromTimestamp(strtotime($order->deadline));
            $completed = Carbon::createFromTimestamp(strtotime($item->updated_at));

            if($completed>$deadline){
                $diff_days = $completed->diffInDays();

            }else{
                $diff_days = 0;
            }
            if($diff_days>15){
                $order->status = 6;
                $item->rating = '4.0';
                $item->comments = 'Auto rated, client did not respond after 15 days';
                $order->update();
                $item->update();
            }
            }
        }
    }
    /**
     * Get orders for revision
     */
    public function getWriterRevision(User $user){
        return Assign::where([
            ['user_id',$user->id],
            ['status',2]
        ])
            ->orderBy('id','desc')
            ->paginate(15);
    }
    /**
     * Get order amount and deadline
     */
    public function calculateCost(Order $order){
        $total = 0;
        $pages = $order->pages;
        $charts = $order->charts;
        $slides = $order->slides;
        $document = $order->document;
        $subject = $order->subject;
        $style = $order->style;
        $language = $order->language;
        $writer_category = $order->writerCategory;
        $rate = $order->rate;
        if($order->spacing==2){
            $spacing = 1;
        }else{
            $spacing = 2;
        }
        $cost = $rate->cost;
        $flat_rate = $cost*$spacing;
        $total+=$flat_rate;
        if($subject->inc_type=='money'){
            $subject_increase = $subject->amount;
        }else if($subject->inc_type=='percent'){
            $subject_increase = $flat_rate*($subject->amount/100);
        }
        $total+=$subject_increase;
        if($document->inc_type=='money'){
            $document_increase = $document->amount;
        }else if($document->inc_type=='percent'){
            $document_increase = $flat_rate*($document->amount/100);
        }
        $total+=$document_increase;
        if($style->inc_type=='money'){
            $style_increase = $style->amount;
        }else if($style->inc_type=='percent'){
            $style_increase = $flat_rate*($style->amount/100);
        }
        $total+=$style_increase;
        if($language->inc_type=='money'){
            $language_increase = $language->amount;
        }else if($language->inc_type=='percent'){
            $language_increase = $flat_rate*($language->amount/100);
        }
        $total+=$language_increase;
        $grand_total = $total*$pages;
        $chart_cost = $charts*$rate->chart;
        $ppt_cost = $slides*$rate->ppt;
        $grand_total+=$chart_cost;
        $grand_total+=$ppt_cost;
        if($writer_category->inc_type=='money'){
            $writer_category_increase = $writer_category->amount;
        }else if($writer_category->inc_type=='percent'){
            $writer_category_increase = $flat_rate*($writer_category->amount/100);
        }
        $grand_total+=$writer_category_increase;
//        dd($grand_total);
        return $grand_total;
    }

    function getAdditionalFeaturesCost($ids,$amount){
        $features = AdditionalFeature::whereIn('id',$ids)->get();
//        dd($features,$amount);
        $total = 0;
        foreach($features as $feature){
            if($feature->inc_type=='money'){
                $total+=$feature->amount;
            }else{
                $total+=($feature->amount/100)*$amount;
            }
        }
        return round($total,2);
    }
    public function getDeadline(Order $order){
        $hours = $order->rate->hours;
        $today = Carbon::now();
//        dd($today,$hours);
        $deadline = $today->addHours($hours);
        return $deadline;
    }
    public function promote(Order $order){
        if($order->discounted>0){
            $percent = 100-$order->discounted;
            $percent = $percent/100;
            $new_amount = $order->amount*$percent;
            $order->amount = number_format($new_amount,2);
            $order->status = 0;
            $order->update();
        }
        $order->status = 0;
        return $order;
    }
}