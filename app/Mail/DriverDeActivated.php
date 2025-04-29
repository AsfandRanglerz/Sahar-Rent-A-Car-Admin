<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverDeActivated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $message;
     public $type;
     public $headerTitle;
 
    public function __construct($message, $type = null)
    {
        $this->message = $message;
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
        return $this->markdown('emails.DriverDeActivated')->with('message',$this->message)->subject('Account Deactivated');
    }
}
