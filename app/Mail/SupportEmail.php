<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $emailSubject;
    public $emailBody;
    public $emailFrom;
    /**
     * Create a new message instance.
     * @param $emailSubject
     * @param $emailBody
     * @param $emailFrom
     * @return void
     */
    public function __construct($emailSubject, $emailBody, $emailFrom)
    {
        $this->emailSubject = $emailSubject;
        $this->emailBody = $emailBody;
        $this->emailFrom = $emailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->emailFrom)
            ->view('emails.contact')->with([
                'subject' => $this->emailSubject,
                'body' => $this->emailBody
            ]);
    }
}
