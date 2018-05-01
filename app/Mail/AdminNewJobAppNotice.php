<?php

namespace App\Mail;

use App\Models\Jobs\Applications;
use App\Models\Jobs\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AdminNewJobAppNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $applicant;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Jobs $job, Applications $applicant)
    {
        $this->job = $job;
        $this->applicant = $applicant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin-new-job-notice')
            ->subject('I have applied for your job. Please review.')
            ->from(Auth::user()->email,Auth::user()->name);
    }
}
