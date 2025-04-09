<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotOTPMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $otp;
    public $type;
    public $headerTitle;

    public function __construct($otp, $type = null)
    {
        $this->otp = $otp;
        $this->type = $type;

        $this->headerTitle = $type === 'driver' ? 'Sahar Rent a Driver' : 'Sahar Rent a Car';
    }
    // {
    //     $this->otp = $otp;
    
        
    // }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.ForgotOTP')->subject('Your OTP Code');
    }
}
