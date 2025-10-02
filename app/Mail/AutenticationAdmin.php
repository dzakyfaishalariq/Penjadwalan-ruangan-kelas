<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutenticationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;
    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject("Verifikasi Akun")
            ->view('emails.verify-email')
            ->with([
                'verificationUrl' => url("/api/admin-verify?token={$this->token}&email={$this->email}"),
                'email' => $this->email,
            ]);
    }

}
