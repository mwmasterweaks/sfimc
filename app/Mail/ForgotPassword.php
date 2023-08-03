<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $Password,$FirstName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($Password,$FirstName)
    {
        $this->Password = $Password;
        $this->FirstName = $FirstName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Password Reset")
                ->with(['FirstName' => $this->FirstName,'Password' => $this->Password])
                ->view('emails.reset-password-email');
    }
}
