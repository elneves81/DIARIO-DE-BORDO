<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Sugestao;

class RespostaSugestaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sugestao;

    public function __construct(Sugestao $sugestao)
    {
        $this->sugestao = $sugestao;
    }

    public function build()
    {
        return $this->subject('Resposta à sua mensagem/sugestão')
            ->view('emails.resposta_sugestao');
    }
}
