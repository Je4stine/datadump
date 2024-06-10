<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\EmailRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
class BulkSendEmails extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $users;
    protected $email_message;
    protected $subject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users,$email_message,$subject)
    {
        //
        $this->users = $users;
        $this->email_message = $email_message;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = $this->users;
        $email_message = $this->email_message;
        $subject = $this->subject;
        $EmailRepository = new EmailRepository();
        if(count($users)){
            foreach($users as $user){
                $EmailRepository->sendEmailNote($user,$subject,$email_message);
            }
        }
    }
}
