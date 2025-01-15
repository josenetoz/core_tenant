<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $password;

    /**
     * Create a new message instance.
     *
     * @param string $password Nova senha do usuário.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sua nova senha')
                    ->view('emails.password-reset')
                    ->with([
                        'password' => $this->password,
                    ]);
    }
}
