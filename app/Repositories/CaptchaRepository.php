<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/05/25
 * Time: 11:47 AM
 */

namespace App\Repositories;


class CaptchaRepository
{
    public function checkRobot($request){
        return $this->googleCapture($request);
    }
    public function googleCapture($request){
        $response = $request->all()['g-recaptcha-response'];
        $data = '{
    	   "secret":"6LeGZSETAAAAAOTkk3YqjEzDjGh-erQepubrqEIV",
    	   "response":"'.$response.'"
    	}';
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LeGZSETAAAAAOTkk3YqjEzDjGh-erQepubrqEIV&response=$response";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSLVERSION , 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
        ));
        $content = @curl_exec($curl);
        print curl_error($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $is_robot = false;
        if($status==200 || $status==201){
            $json_response = json_decode($content);
            $is_robot = $json_response->success;
        }else{

        }
        return $is_robot;
    }

}