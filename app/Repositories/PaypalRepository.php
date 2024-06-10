<?php

/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/03/21
 * Time: 12:38 AM
 */

namespace App\Repositories;

use App\Order;
use App\Payout;
use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Assign;
use Illuminate\Support\Facades\Storage;

class PaypalRepository
{
    public $request;
    protected $user;
    protected $order;

    //sandbox credentials
    protected $client_id = "AU_T4yTOmD_WuH8ZMW_D7OhFO4fMmAtC0VG61riHuYTiFz_XvPuVhmK0FrA2BXQ87VBcO4brUO46sbnZ";
    protected $secret = "EDmYTkopv8JMu4EISFxzoLdfSeCOGoW9gKWcpFFosrwKaqvlcnEZWYp2V5zTHUg_onyRQ2k_4y2VsTVd";
    protected $endpoint = "https://api.sandbox.paypal.com";

    protected $return_url;
    protected  $cancel_url;
    protected $website;
    public function __construct()
    {
        $config = json_decode(Storage::disk('local')->get('system_files/config.json'));
        if ($config) {
            $paypal = $config->paypal;
            $this->endpoint = $paypal->endpoint;
            $this->client_id = $paypal->client_id;
            $this->secret = $paypal->secret;
        }
        $web_repo = new WebsiteRepository();
        $this->website = $web_repo->getWebsite();
        $this->user = Auth::user();
        $this->return_url =  action('ClientController@savePayment');
        $this->cancel_url =  url("stud/unpaid");
    }

    public function prepare(Request $request, Order $order)
    {
        $this->order = $order;
        $this->request = $request;
        $paid = $order->getTotalPaid();
        $amount = $order->amount - $paid;
        $currency = $order->currency;
        $sku = $order->id;
        if ($currency) {
            $amount = $currency->usd_rate * $amount;
            $abbrev = $currency->abbrev;
        } else {
            $abbrev = 'USD';
        }
        if ($order->partial == 1 && $paid == 0  && $request->mile == null) {
            if ($this->website->deposit) {
                $amount = $amount * ($this->website->deposit / 100);
            } else {
                $amount = $amount * 0.3;
            }
        }
        $description = 'order#' . $order->id;
        if ($request->mile) {
            $milestone = $order->progressiveMilestones()->find($request->mile);
            $amount = $milestone->amount;
            $description = "part#" . $request->mile . " Order#" . $order->id . ' Payment';
            $sku = $order->id . '_part_' . $request->mile;
        }
        $amount = number_format($amount, 2);
        $data = '{
             "intent":"sale",
             "redirect_urls":{
                "return_url":"' . $this->return_url . '",
                "cancel_url":"' . $this->cancel_url . '"
             },
            "payer":{
                 "payment_method":"paypal"
            },
            "transactions":[
                {
             "amount":{
             "total":"' . $amount . '",
             "currency":"' . $abbrev . '",
             "details": {
                    "subtotal": "' . $amount . '",
                    "tax": "0.00",
                    "shipping": "0.00"
                }
             },
            "description":"order#' . $order->id . '",
            "item_list": {
                "items":[
                    {
                        "quantity":"1",
                        "name":"' . $description . '",
                        "price":"' . $amount . '",
                        "sku":"' . $sku . '",
                        "currency":"' . $abbrev . '"
                    }
                ]
            }
          }
         ]
    }';
        $data = stripslashes($data);
        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $this->getAccessToken(),
        );
        $url = $this->endpoint . "/v1/payments/payment";
        $response = $this->getCurl($url, $data, "POST", $header);
        foreach ($response->links as $link) {
            if ($link->rel == 'approval_url') {
                ?>
<p style="color: green">Redirecting. Please wait...</p>
<meta http-equiv="refresh" content="0;url=<?php echo $link->href; ?>">
<?php
                die();
            }
        }
    }
    public function payWriter(User $user, $amount, $payment_for, $subject)
    {
        $profile = $user->profile;
        $this->return_url =  action('ClientController@tipSuccess');
        $this->cancel_url =  action('ClientController@cancelTip');
        //        $user = $user->find(1);
        $data = '{
    "sender_batch_header": {
        "email_subject": "' . $subject . '"
      },
      "items": [
        {
          "recipient_type": "EMAIL",
          "amount": {
            "value": ' . $amount . ',
            "currency": "USD"
          },
          "receiver": "' . $user->email . '",
          "note": "Order Payments",
          "sender_item_id": "' . $user->id . '"
        }
      ]
    }';
        $data = stripslashes($data);
        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $this->getAccessToken(),
        );
        $url = $this->endpoint . "/v1/payments/payouts?sync_mode=true";
        $response = (object) $this->getCurl($url, $data, "POST", $header)->items[0];

        $user->payments()->create([
            'payer_id' => Auth::User()->id,
            'transaction_reference' => $response->transaction_id,
            'amount' => $response->payout_item->amount->value,
            'state' => $response->transaction_status,
            'method' => 'paypal',
            'payment_for' => $payment_for,
        ]);
        return $response->transaction_status;
    }
    public function tipWriter(Assign $assign, $amount, $currency, Request $request)
    {
        $this->request = $request;
        $this->return_url =  action('ClientController@tipSuccess');
        $this->cancel_url =  action('ClientController@cancelTip');
        $order = $assign->order;
        $writer = $assign->user;
        $data = '{
             "intent":"sale",
             "redirect_urls":{
                "return_url":"' . $this->return_url . '",
                "cancel_url":"' . $this->cancel_url . '"
             },
            "payer":{
                 "payment_method":"paypal"
            },
            "transactions":[
                {
             "amount":{
             "total":"' . $amount . '",
             "currency":"' . $currency . '",
             "details": {
                    "subtotal": "' . $amount . '",
                    "tax": "0.00",
                    "shipping": "0.00"
                }
             },
            "description":"Tip writer#' . $writer->id . ' For job well Done",
            "item_list": {
                "items":[
                    {
                        "quantity":"1",
                        "name":"Tip for Writer#' . $writer->id . '",
                        "price":"' . $amount . '",
                        "sku":"' . $assign->id . '",
                        "currency":"' . $currency . '"
                    }
                ]
            }
          }
         ]
    }';
        $data = stripslashes($data);
        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $this->getAccessToken(),
        );
        $url = $this->endpoint . "/v1/payments/payment";
        $response = $this->getCurl($url, $data, "POST", $header);
        foreach ($response->links as $link) {
            if ($link->rel == 'approval_url') {
                ?>
<p style="color: green">Redirecting. Please wait...</p>
<meta http-equiv="refresh" content="0;url=<?php echo $link->href; ?>">
<?php
                die();
            }
        }
    }
    public function topUp(Request $request, $amount)
    {
        $this->request = $request;
        $this->return_url =  action('ClientController@executeTopUp');
        $this->cancel_url =  action('ClientController@index');
        $amount = round($amount, 2);
        $user = $this->user;
        $data = '{
             "intent":"sale",
             "redirect_urls":{
                "return_url":"' . $this->return_url . '",
                "cancel_url":"' . $this->cancel_url . '"
             },
            "payer":{
                 "payment_method":"paypal"
            },
            "transactions":[
                {
             "amount":{
             "total":"' . $amount . '",
             "currency":"USD",
             "details": {
                    "subtotal": "' . $amount . '",
                    "tax": "0.00",
                    "shipping": "0.00"
                }
             },
            "description":"' . $user->email . ' Account Top Up",
            "item_list": {
                "items":[
                    {
                        "quantity":"1",
                        "name":"' . $user->email . ' Account Top Up",
                        "price":"' . $amount . '",
                        "sku":"' . $user->id . '",
                        "currency":"USD"
                    }
                ]
            }
          }
         ]
    }';
        $data = stripslashes($data);
        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $this->getAccessToken(),
        );
        $url = $this->endpoint . "/v1/payments/payment";
        $response = $this->getCurl($url, $data, "POST", $header);
        foreach ($response->links as $link) {
            if ($link->rel == 'approval_url') {
                ?>
<p style="color: green">Establishing a secure link with Paypal. Please wait...</p>
<meta http-equiv="refresh" content="0;url=<?php echo $link->href; ?>">
<?php
                die();
            }
        }
    }

    public function charge($payerID, $paymentID)
    {
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getAccessToken()
        );
        $data = '{ "payer_id" : "' . $payerID . '" }';
        $url = $this->endpoint . '/v1/payments/payment/' . $paymentID . '/execute/';
        // $url = 'https://api.paypal.com/v1/payments/payment/'.$paymentID.'/execute/';
        $response = $this->getCurl($url, $data, "POST", $header);
        return $response;
    }
    public function getAccessToken()
    {
        $request = $this->request;
        if ($request->session()->has('access_token')) {
            $access_token = $request->session()->get('access_token');
        } else {
            if ($this->endpoint == 'https://api.paypal.com') {
                $url = "https://$this->client_id:$this->secret@api.paypal.com/v1/oauth2/token?grant_type=client_credentials";
            } else {
                $url = "https://$this->client_id:$this->secret@api.sandbox.paypal.com/v1/oauth2/token?grant_type=client_credentials";
            }
            $creds = $this->getCurl($url);
            $access_token = $creds->access_token;
            $request->session()->put(['access_token' => $access_token]);
        }
        return $access_token;
    }

    protected function getCurl($url, $data = null, $method = "POST", $header = null)
    {
        if (!$header) {
            $header = array(
                'Accept: application/json',
                'Accept-Language: en_US',
            );
        }
        //        var_dump($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $content = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $json_response = null;
        if ($status == 200 || $status == 201) {
            $json_response = json_decode($content);
            return $json_response;
        } else {
            //            return view('errors.paypal',json_decode($content,true));
            //            exit;
            $mail = new EmailRepository();
            $error_details = json_decode($content, true);
            $error_details['order_id'] = @$this->order->id;
            $error_details['user_id'] = @$this->user->id;
            $view = (string) view('errors.array_table', ['data' => (array) $error_details]);
            $mail->sendAdminNote('A client tried to pay for order but failed, please check on paypal payments<br/><strong>Error Details</strong><br/>' . $view, 'Payment Failure', 'kibetian8@gmail.com');
            echo view('errors.common', ['data' => $view]);
            exit;
        }
    }
    //    protected function getCurl($url,$data=null, $method="POST", $header=null){
    //        if(!$header){
    //            $header = array(
    //                'Accept: application/json',
    //                'Accept-Language: en_US',
    //            );
    //        }
    //        //download cert from https://www.paypal-knowledge.com/infocenter/index?page=content&widgetview=true&id=FAQ1516&viewlocale=en_US
    //        $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_VERBOSE, 1);

    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . "\api.paypal.com_SHA-1_12132016.pem");
    // curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_POST, 1);



    // //$requeststring has refund specific fields

    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // // Get response from the server.
    // $content = curl_exec($ch);
    // $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //        $json_response = null;
    //        if($status==200 || $status==201){
    //            $json_response = json_decode($content);
    //        }else{
    //            dd($header,$data,$url,$content,$status);
    //        }
    //        return $json_response;


    //    }
}
