<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/09/10
 * Time: 7:34 PM
 */

namespace App\Repositories;

use App\Payout;
use Auth;
use App\User;
use Illuminate\Support\Facades\Storage;
class AdaptivePayment
{
    protected $endpoint;
    protected $return_url;
    protected $cancel_url;
    protected $user_id;
    protected $password;
    protected $signature;
    protected $app_id;
    protected $user;

    public function __construct(){
        $config = json_decode(Storage::disk('local')->get('system_files/config.json'));
        if($config){
            $paypal = $config->withdrawals->paypal;
            $this->user_id = $paypal->user_id;
            $this->password = $paypal->password;
            $this->signature = $paypal->signature;
            $this->app_id = $paypal->app_id;
            $this->endpoint = $paypal->endpoint;
        }
    }
    public function payWriter(User $user,$amount,$email,$is_manual=0){
        $this->user = $user;
//        $profile = $user->profile;
        $this->return_url = action('PaymentController@executePayment');
        $this->cancel_url = action('PaymentController@executePayment');
//        $user = $user->find(1);
        $amount = round($amount,2);
        $data = '{
                          "actionType":"PAY",                             
                          "currencyCode":"USD",                            
                          "receiverList":{
                            "receiver":[{
                              "amount":"'.$amount.'",                            
                              "email":"'.$email.'"  
                            }]
                          },
                          "returnUrl":"'.$this->return_url.'", 
                          "cancelUrl":"'.$this->cancel_url.'", 
                          "requestEnvelope":{
                          "errorLanguage":"en_US",                          
                          "detailLevel":"ReturnAll"  
                          }
                     }
            ';
        $data = stripslashes($data);
       $header = $this->getHeaders();
        $url = $this->endpoint."AdaptivePayments/Pay";
        $response = $this->getCurl($url, $data, "POST", $header);
        $payment = $user->payments()->create([
            'transaction_reference' => $response->payKey,
            'amount' => $amount,
            'state' => $response->paymentExecStatus,
            'method' => 'paypal',
            'payment_for' => 'Account Withdrawal',
            'email' => $email,
            'pay_key'=>$response->payKey
        ]);
        $payment->is_manual = $is_manual;
        $payment->update();
        $email = new EmailRepository();
        if($this->endpoint == 'https://svcs.sandbox.paypal.com/'){
            $pay_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey;
        }else{
            $pay_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey;
        }
        $email->sendAdminNote($user->email.' Has submitted a withdrawal request of <strong>$'.$amount.'<strong> Please click <a href="'.$pay_url.'">Here</a> to process  <br/>','Payment Request');
       return $response->paymentExecStatus;
    }

    public function renewPayment(Payout $payout,$email){
//        $profile = $user->profile;
        $this->return_url = action('PaymentController@executePayment');
        $this->cancel_url = action('PaymentController@executePayment');
//        $user = $user->find(1);
        $amount = round($payout->amount,2);
        $data = '{
                          "actionType":"PAY",                             
                          "currencyCode":"USD",                            
                          "receiverList":{
                            "receiver":[{
                              "amount":"'.$amount.'",                            
                              "email":"'.$email.'"  
                            }]
                          },
                          "returnUrl":"'.$this->return_url.'", 
                          "cancelUrl":"'.$this->cancel_url.'", 
                          "requestEnvelope":{
                          "errorLanguage":"en_US",                          
                          "detailLevel":"ReturnAll"  
                          }
                     }
            ';
        $data = stripslashes($data);
       $header = $this->getHeaders();
        $url = $this->endpoint."AdaptivePayments/Pay";
        $response = $this->getCurl($url, $data, "POST", $header);
        $payout->update([
            'transaction_reference' => $response->payKey,
            'amount' => $amount,
            'state' => $response->paymentExecStatus,
            'pay_key'=>$response->payKey
        ]);
        $email = new EmailRepository();
        if($this->endpoint == 'https://svcs.sandbox.paypal.com/'){
            $pay_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey;
        }else{
            $pay_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment&paykey=".$response->payKey;
        }
       return $response->paymentExecStatus;
    }
    public function checkApproved(){
        $payments = Payout::where('state','CREATED')->get();
        foreach($payments as $payment){
            $header = $this->getHeaders();
            $url = $this->endpoint.'AdaptivePayments/PaymentDetails';
            $data = '{"payKey":"'.$payment->pay_key.'","requestEnvelope":{
                          "errorLanguage":"en_US",                          
                          "detailLevel":"ReturnAll"  
                          }}';
            $response = $this->getCurl($url,$data,'POST',$header);
            if($response->status == 'COMPLETED'){
                $user = $payment->user;
                $email = new EmailRepository();
                $email->sendEmailNote($user,'Payment Processed','Your payment of <strong>'.$payment->amount.'</strong> Has been processed by admin');
                $payment->state = 'COMPLETED';
                $payment->payer_id = Auth::user()->id;
                $payment->update();
            }
        }
    }
    public function getHeaders(){
        $header = array(
            'X-PAYPAL-SECURITY-USERID: '.$this->user_id,
            'X-PAYPAL-SECURITY-PASSWORD: '.$this->password,
            'X-PAYPAL-SECURITY-SIGNATURE: '.$this->signature,
            'X-PAYPAL-REQUEST-DATA-FORMAT: JSON',
            'X-PAYPAL-APPLICATION-ID: '.$this->app_id
        );
        return $header;
    }
    protected function getCurl($url,$data=null, $method="POST", $header=null){
        if(!$header){
            $header = array(
                'Accept: application/json',
                'Accept-Language: en_US',
            );
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
        $content = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $json_response = null;
        $json_response = @json_decode($content);
        if($status<205 && @$json_response->error == null){
            $json_response = json_decode($content);
        }else{
            $mail = new EmailRepository();
            $mail->sendAdminNote('A writer/admin payout failed, please check on paypal payouts<br/><strong>Error Details</strong><br/>'.$content,'Payment Failure','kibetian8@gmail.com');
            dd($content);
            exit;
        }
        return $json_response;
    }
}