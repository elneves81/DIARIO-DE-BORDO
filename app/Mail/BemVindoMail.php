<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BemVindoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $senha;

    public function __construct($user, $senha = null)
    {
        $this->user = $user;
        $this->senha = $senha;
    }

    public function build()
    {
        return $this->subject('Bem-vindo ao Diário de Bordo')
            ->view('emails.bemvindo')
            ->with(['senha' => $this->senha]);
    }
}
