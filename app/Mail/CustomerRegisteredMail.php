<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $email;
    public $phone;
    public $type;
     public $headerTitle;
    public function __construct($name, $email, $phone, $type = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->type = $type;

        $this->headerTitle = $type === 'driver' ? 'Sahar Rent a Driver' : 'Sahar Rent a Car';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.customer.registered')->subject('Account Created!')->with(['headerTitle' => $this->headerTitle,]);
    }
}
