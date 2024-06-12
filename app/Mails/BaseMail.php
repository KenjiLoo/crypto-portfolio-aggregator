<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this
            // ->view('emails.reset-admin-password')
            // ->from(config('custom.email.noreply'), config('custom.email.name'))
            // ->subject('Subject');
    }
}
