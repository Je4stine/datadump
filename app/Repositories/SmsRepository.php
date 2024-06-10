<?php
/**
 * Created by PhpStorm.
 * User: kibet
 * Date: 2/4/17
 * Time: 5:12 PM
 */

namespace App\Repositories;
use App\SentSms;
use Nexmo\Client;

class SmsRepository
{
    protected $client;
    protected $from;
    function __construct()
    {
        $this->from = env('SMS_FROM');
        $this->client = new Client(new Client\Credentials\Basic(env('NEXMO_API_KEY'),env('NEXMO_API_SECRET')));
    }

    public function sendSms($user,$message){
        $from = $this->from;
        if(!$from)
            $from = 'NXSMS';
        $message_details = [
            'to'=>'+13852444402',
            'from'=>$from,
            'text'=>$message." ".$user->website->name
        ];
//        try {
            $status = $this->client->message()->send($message_details);
            $sent_sms = new SentSms();
            $sent_sms->user_id = $user->id;
            $sent_sms->phone = $user->phone;
            $sent_sms->message = $message;
            $sent_sms->sent = 1;
            $sent_sms->save();
            return $status;
//        } catch (\Exception $e) {
//            return false;
//        }

    }
}