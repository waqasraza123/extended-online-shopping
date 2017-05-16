<?php

namespace App\Jobs;

use App\Mail\SupportEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SupportEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $subject;
    protected $body;
    protected $from;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $body, $from)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->from = $from;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('waqasraza123@gmail.com')->send(new SupportEmail($this->subject, $this->body, $this->from));
    }
}
