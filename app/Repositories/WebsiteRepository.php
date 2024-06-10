<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/04/09
 * Time: 6:37 PM
 */

namespace App\Repositories;
use App\Order;
use App\Website;
use Storage;
use URL;
class WebsiteRepository
{
    protected $url;
    protected $website;
    public function __construct()
    {
        $url = URL::to('/');
        $url = str_replace('www.','',$url);
        $url = str_replace('https','',$url);
        $url = str_replace('http','',$url);
        $url = str_replace('://','',$url);
        $this->url = $url;
        $website = Website::where('home_url','LIKE',"%".$this->url)->first();
        $this->website = $website;
        if(!$website){
            $order = new Order();
            //$order->setWebsite();
        }
    }

    public function getWebsiteId(){

        return $this->website->id;
    }

    public function getWebsite(){
        return $this->website;
    }
}