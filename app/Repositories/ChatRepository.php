<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 2016/08/26
 * Time: 4:26 AM
 */

namespace App\Repositories;


class ChatRepository
{
    public function checkFiltered($message){
        $message = strtolower($message);
        $filtered = false;
        $words = explode(' ',$message);
        foreach($this->getBadWords() as $badWord){
            $badWord = strtolower($badWord);
            if(strpos($message,$badWord) !== false){
                $filtered = true;
            }
        }
        if($filtered == false){
            foreach($words as $word){
                if(filter_var($word,FILTER_VALIDATE_EMAIL)){
                    $filtered = true;
                }
                foreach($this->getBadRegexes() as $regex){
                    if(preg_match($regex,$word)){
                        $filtered = true;
                    }
                }
            }
        }
        return $filtered;
    }

    public function checkBadWords(){


    }

    public function checkBadRegexes(){

    }

    public function getBadWords(){
        $bad_words = [
            "email",
            "phone",  "my website",
            "pay via", "skype", "Email", "mail", "emeil", "imeil", "emmail", "mmail",
            "Emmeil", "immeil", "Gmail", "gymail",
            "@", "PayPal", "peipol","ppal","@google",
            "@gmail","yahoo", "dot-com", "dotcom", ".com","comu",".net","yahuu", "docom",
            "@yahoo", "@hotmail", "@outlook", "athotmail", "atoutlook", "atgmail","atyahoo","atyahuu","fuck", "idiot", "bullshit", "call me", "call on", "whatsapp", "instagram","login",
            "crap", "bitch"
        ];
        return $bad_words;
    }

    public function getBadRegexes(){
        $bad_regexes = [
            '/^(\+[1-9][0-9]*(\([0-9]*\)|-[0-9]*-))?[0]?[1-9][0-9\- ]*$/',
            '/^\(\d{1,2}(\s\d{1,2}){1,2}\)\s(\d{1,2}(\s\d{1,2}){1,2})((-(\d{1,4})){0,1})$/',
        ];
        return [];
    }
}