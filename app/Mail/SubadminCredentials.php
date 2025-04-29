<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubadminCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $email;
    public $password;
    public $phone;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $email, $phone, $password, $type = null)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.SubadminCredentials')->subject('Account Created');
    }
}
