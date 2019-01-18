<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormResponse extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private $mailBody;

    /**
     * Create a new message instance.
     *
     * @param string $mailBody
     */
    public function __construct(string $mailBody)
    {
        $this->mailBody = $mailBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@exeter.ac.uk')
            ->view('emails.formResponse', ['body' => $this->mailBody]);
    }
}
