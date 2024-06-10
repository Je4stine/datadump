<?php

namespace App\Http\Controllers;

use App\GmailContact;
use App\Repositories\EmailRepository;
use App\User;
use Dropbox\Client;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GmailController extends Controller
{
    //
    protected $contacts = [];
    public function fetchContacts(Request $request){
        $code = $request->code;
        $google_client = new \Google_Client();
        $google_client->setClientId('179577202552-dg4tc2hu7u86ki2i8qv0gm028v9hbh7u.apps.googleusercontent.com');
        $google_client->setClientSecret('_0Teq6eSDaqcctoBqtZ-Vw6S');
        $google_client->setRedirectUri(url('api/gmail'));
        $scopes = $google_client->addScope('https://www.googleapis.com/auth/contacts.readonly');
        $token = $google_client->fetchAccessTokenWithAuthCode($code);
        $url = 'https://www.google.com/m8/feeds/contacts/default/full?&alt=json&v=3.0&max-results=500&oauth_token='.$token['access_token'];
        $feed = json_decode($this->curlGet($url),true)['feed'];
        $contact_count = $feed['openSearch$totalResults']['$t'];
        $per_page = $feed['openSearch$itemsPerPage']['$t'];
        $index = $feed['openSearch$startIndex']['$t'];
        $this->saveContacts($feed);
        return redirect("stud/affiliate?action=invite_emails");
    }

    public function inviteFriends(){
        $google_client = new \Google_Client();
        $google_client->setClientId('179577202552-dg4tc2hu7u86ki2i8qv0gm028v9hbh7u.apps.googleusercontent.com');
        $google_client->setClientSecret('_0Teq6eSDaqcctoBqtZ-Vw6S');
        $google_client->setRedirectUri(url('api/gmail'));
        $scopes = $google_client->addScope('https://www.googleapis.com/auth/contacts.readonly');
        $url = $google_client->createAuthUrl();
        return response()->redirectTo($url);
    }

    protected function saveContacts($feed){
        $user = Auth::user();
        foreach($feed['entry'] as $contact){
            $contact_details = [];
            if(isset($contact['gd$email']) && User::where('email',$contact['gd$email'])->count() == 0 && GmailContact::where('email',$contact['gd$email'])->count() == 0){
                $contact_details['name']=$contact['title']['$t'];
                $contact_details['email']= $contact['gd$email'][0]['address'];
                $user->gmailContacts()->updateOrCreate(['email'=>$contact_details['email']],$contact_details);
            }

        }
    }

    public function startInviting(){
        $contacts = GmailContact::where([
            ['status','=',1],
            ['invited','=',0],
            ['registered','=',0],
        ])->paginate(10);
       $emailer = new EmailRepository();
       foreach($contacts as $contact){
           $emailer->sendGmailInvite($contact);
           $contact->invited = 1;
           $contact->update();
       }
       echo "Done!";
    }

   protected function curlGet($url){
       $header = array(
           'Accept-Language: en_US',
       );
       $curl = curl_init($url);
       curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HEADER, 0);
       curl_setopt($curl, CURLOPT_POSTFIELDS, null);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
       $content = curl_exec($curl);
       return $content;
   }
}