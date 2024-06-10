<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Google\Google_Client;
use Illuminate\Foundation\Auth\User;

class BackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:data {entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * google access token to be set
     */
    protected $access_token;
    /**
     * Create a new command instance.
     *
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAccessToken();
    }

    /**
     * Execute the console command.
     *
     *
     */
    public function handle()
    {
        $entity = $this->argument('entity');
        if($entity=='users'){
            $this->backupUsers();
        }
    }

    public function backupUsers(){
        $all_users = User::get();
        $listFeed = $this->getWorkSheet('users')->getListFeed();
        echo "User Backup Initialized..\n";
        $bar = $this->output->createProgressBar(count($all_users));
        foreach($all_users as $user){
            $user_data = json_decode(json_encode($user),true);
            $user_data['createdat']=$user->created_at;
            $user_data['updatedat']=$user->updated_at;
            $listFeed->insert($user_data);
            $bar->advance();
        }
        $bar->finish();
        echo "User Backup Completed\n";
    }
    public function getWorkSheet($title){
        $serviceRequest = new DefaultServiceRequest($this->access_token);
        ServiceRequestFactory::setInstance($serviceRequest);
        $spreadsheetService = new SpreadsheetService();
        $spreadsheetFeed = $spreadsheetService->getSpreadsheets();
        $spreadsheet = $spreadsheetFeed->getByTitle('mybetmasters');
        $worksheetFeed = $spreadsheet->getWorksheets();
        $worksheet = $worksheetFeed->getByTitle($title);
        return $worksheet;
    }
    public function setAccessToken(){
        echo "Setting access token..\n";
        include(getcwd().'/vendor/asimlqt/Auth/Google_Client.php');
        $client = new \Google_Client();
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $refresh_token = env('GOOGLE_REFRESH_TOKEN');
        $redirectUrl = env('GOOGLE_REDIRECT_URL');
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUrl);
        $client->setScopes(array('https://spreadsheets.google.com/feeds'));
        $client->refreshToken($refresh_token);
        $json= json_decode($client->getAccessToken(),true);
        $accessToken = $json['access_token'];
        $this->access_token = $accessToken;
        echo "Access Token Set.. \n";
    }
}
