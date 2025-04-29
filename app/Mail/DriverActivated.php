<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverActivated extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $type;
    public $headerTitle;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $type = null)
    {
        $this->message = $message;
        $this->type = $type;
    
    // Set dynamic header title
     $this->headerTitle = $type === 'driver' ? 'Sahar Rent a Driver' : 'Sahar Rent a Car';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.DriverActivated')->with('message',$this->message)->subject('Account Activated');
    }
}
