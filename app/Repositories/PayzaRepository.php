<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/09/20
 * Time: 10:56 PM
 */

namespace App\Repositories;


use App\Order;
use Carbon\Carbon;

class PayzaRepository
{
    protected $ipn_url = 'https://secure.payza.com/ipn2.ashx';
    public function confirmCode($code){
        $url = $this->ipn_url;
        $token = 'token='.urlencode($code);
        $this->getCurl($url,$token);
    }

    protected function getCurl($url,$token){
        $response = '';
        $email_repo = new EmailRepository();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        if(strlen($response) > 0)
        {
            if(urldecode($response) == "INVALID TOKEN")
            {
                $email_repo->sendAdminNote('Payza Payment Error has occured');
            }
            else
            {
                $response = urldecode($response);
                $aps = explode("&", $response);
                $info = array();
                foreach ($aps as $ap)
                {
                    $ele = explode("=", $ap);
                    $info[$ele[0]] = $ele[1];
                }
                $transactionReferenceNumber = $info['ap_referencenumber'];
                $myItemCode = $info['ap_itemcode'];
                $amount = $info['ap_amount'];
                $order = Order::find($myItemCode);
                $currency = $info['ap_currency'];
                $ap_transactionstate = $info['ap_transactionstate'];
                $transactionDate = $info['ap_transactiondate'];
                if($order->currency){
                    $usd_rate = $order->currency->usd_rate;
                }else{
                    $usd_rate = 1;
                }
                $txn = $order->paypalTxns()->create([
                    'amount'=>$amount,
                    'txn_id'=>$transactionReferenceNumber,
                    'state'=>$ap_transactionstate,
                    'create_time'=>$transactionDate,
                    'currency'=>$currency,
                    'usd_rate'=>$usd_rate
                ]);
                $email_repo->sendGeneralEmail('client_order_paid','Order Payment Succeeded',$order->user,$order);
                $message = "Hello Admin<br/> Order#$order->id has been paid for using payza, amt= $amount $currency";
                $email_repo->sendAdminNote($message,'Payment Notice','kibethosea8@gmail.com');
                $o_user = $order->user;
                $bidmapper= $order->bidMapper;
                if($bidmapper->status == 0){
                    $bidmapper->status = 1;
                }
                $bidmapper->allowed = json_encode([$order->writer_category_id]);
                $bidmapper->update();
                $order->paid = 1;
                $order->update();
                $o_user->awardReferrer();
                return $txn->toJson();
            }
        }
        else
        {
            //something is wrong, no response is received from Payza
        }
    }
}