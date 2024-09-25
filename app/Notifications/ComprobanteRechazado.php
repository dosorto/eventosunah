<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComprobanteRechazado extends Notification
{
    use Queueable;

    protected $inscripcion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Comprobante de Pago Rechazado')
                    ->greeting('Hola ' . $this->inscripcion->persona->nombre . ',')
                    ->line('Lamentamos informarte que tu comprobante de pago para el evento "' . $this->inscripcion->evento->nombreevento . '" ha sido rechazado.')
                    ->line('Por favor, verifica tu comprobante y vuelve a intentarlo.')
                    ->action('Ver Evento', url('/eventoVista'))
                    ->line('Gracias por tu comprensión.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}