<?php

namespace App\Mail;

use App\Models\Jobs\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareJob extends Mailable
{
    use Queueable, SerializesModels;
    public $job;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Jobs $job)
    {
        $this->job =$job;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.share-job');
    }
}
