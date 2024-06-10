<?php

namespace App\Console\Commands;

use Dropbox\WriteMode;
use Illuminate\Console\Command;
use Dropbox\Client;
use Illuminate\Support\Facades\DB;

class DropboxBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dropbox:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup system database in dropbox';
    /**
     * Bar progress instance
     */
    protected $bar;
    /**
     * Create a new command instance.
     *
     *
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        require_once getcwd()."/vendor/Dropbox/autoload.php";
        $this->info('Dumping Files');
        $access_token = env('DROPBOX_ACCESS');
        $zip_file = $this->mysqlDump();
        $this->info("\n Dump Completed");
        $dbx = new Client($access_token,"PHP-Example/1.0");
        $this->bar->advance();
        $f = fopen($zip_file, "rb");
        $exploded = explode('/',$zip_file);
        $file_name = $exploded[count($exploded)-1];
        $this->info("\nUploading..");
        $upload_dir = date('Y/M/d');
        $result = $dbx->uploadFile('/'.$upload_dir.'/'.$file_name, WriteMode::add(), $f);
        $this->info("\nUpload Completed");
        fclose($f);
        exec('rm -r /tmp/mybetmasters');
        $uploaded_size = $result['size'];
        $this->bar->finish();
        $this->info("\nBackup successful: ".$uploaded_size.' Zipped sql file uploaded');
    }
    public function mysqlDump(){
        $dbHost = env('DB_HOST');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');
        $table_list = DB::select('SHOW TABLES');
        $folder = date('h_i_a');
        $directory = "/tmp/mybetmasters/".$folder;
        exec('mkdir /tmp/mybetmasters');
        exec('mkdir '.$directory);
        $tmpDir = $directory;
        $this->bar = $this->output->createProgressBar(count($table_list)+3);
        $xte = "Tables_in_".$dbName;
        foreach($table_list as $table_obj){
            $table = $table_obj->$xte;
            $sqlFile = $tmpDir."/".$table.'.sql';
            $backupFilename = $folder.'.zip';
            $createBackup = "mysqldump -h ".$dbHost." -u ".$user." --password='".$password."' ".$dbName." ".$table." --> ".$sqlFile;
            exec($createBackup);
            $this->bar->advance();
        }
        $createZip = "cd /tmp/mybetmasters/ && zip -r $backupFilename $folder";
        exec($createZip);
        $this->bar->advance();
        return "/tmp/mybetmasters/".$backupFilename;
    }
}
