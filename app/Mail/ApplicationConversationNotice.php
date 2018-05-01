<?php

namespace App\Mail;

use App\Models\Jobs\Applications;
use App\Models\Jobs\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationConversationNotice extends Mailable
{
    use Queueable, SerializesModels;


    protected  $application;
    protected $job;
    protected $target;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Jobs $job, Applications $application,$target)
    {
        $this->job = $job;
        $this->application = $application;
        $this->target = $target;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->target =='admin'){
            $message = "<h4>An applicant has posted a message</h4>";
            $message .="Please access the application on the link below.";
            return $this->markdown('emails.app-conversation-notice')
                ->with(['message'=>$message,'job'=>$this->job,'application'=>$this->application])
                ->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                ->subject('User posted message regarding application');
        }else{
            $message = "<h4>You have a message posted to your application.</h3>";
            $message .="Access you application using the link below";
            return  $this->markdown('emails.app-conversation-notice')
                ->with(['message'=>$message,'job'=>$this->job,'application'=>$this->application])
                ->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'))
                ->subject('You have a message regarding application');
        }
    }
}
