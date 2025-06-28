<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Viagem;

class ViagemStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $viagem;
    public $oldStatus;
    public $newStatus;

    public function __construct(Viagem $viagem, $oldStatus, $newStatus)
    {
        $this->viagem = $viagem;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status da sua viagem foi alterado')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('O status da sua viagem para ' . $this->viagem->destino . ' foi alterado:')
            ->line('De: ' . ucfirst($this->oldStatus) . ' para: ' . ucfirst($this->newStatus))
            ->action('Ver detalhes', url(route('viagens.show', $this->viagem->id)))
            ->line('Se não foi você quem solicitou, entre em contato com o administrador.');
    }

    public function toArray($notifiable)
    {
        return [
            'viagem_id' => $this->viagem->id,
            'destino' => $this->viagem->destino,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
