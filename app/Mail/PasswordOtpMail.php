<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $otp) {}

    public function build()
    {
        return $this->subject('Your Password Reset OTP')
            ->view('emails.password_otp')
            ->with(['otp' => $this->otp]);
    }
}
