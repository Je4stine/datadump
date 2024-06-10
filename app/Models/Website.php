<?php

namespace App;

use App\Repositories\WebsiteRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Website extends Model
{
    //
    protected $fillable = ['name','home_url','role','layout','telephone','email','logo'];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function promotions(){
        return $this->hasMany(Promotion::class);
    }

    public function punchlines(){
        return $this->hasMany(Assurance::class);
    }

    public function emails(){
        return $this->hasMany(Email::class);
    }

    public function getRedeemRate(){
        $rate = $this->redeem_rate ? $this->redeem_rate:10;
        return $rate;
    }

    public function getPointPay(){
        $point_pay_amount = $this->point_pay_amount ? $this->point_pay_amount:10;
        return $point_pay_amount;
    }
    public function getReferralPoints(){
        $points_per_referral = $this->points_per_referral ? $this->points_per_referral:10;
        return $points_per_referral;
    }
    public function getAuthorPoints(){
        $author_points = $this->author_points ? $this->author_points:10;
        return $author_points;
    }
}
