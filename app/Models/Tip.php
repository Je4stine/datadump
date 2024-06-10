<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Assign;
class Tip extends Model
{
    //
    protected $fillable = ['amount','txn_id','state','create_time','currency','usd_rate'];

    public function assign(){
        return $this->belongsTo(Assign::class);
    }
}
