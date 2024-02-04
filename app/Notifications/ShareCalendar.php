<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShareCalendar extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     *  @var String
     */
    public $code;

    /**
     * @var Object
     */
    public $calendar;

    /**
     * Create a new notification instance.
     */
    public function __construct($code, $calendar)
    {
        $this->code = $code;
        $this->calendar = $calendar;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__("Aignado Nuevo Calendario"))
            ->greeting(__("Hola, "), $notifiable->name)
            ->line(__("Has sido agregado a una tarea. Por favor ingresa al link que se encuentra debajo para marcar la asistencia"))
            ->line(__('Tu codigo de session es el siguiente : ') . $this->code)
            ->action('Marcar Assitencia', url($this->generateURL($notifiable)))
            ->line('Gracias por usar nuestros servicios');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function generateURL($user)
    {
        $query = http_build_query([
            'token' => $user->token,
            'email' => $user->email,
        ]);

        return route('calendar.show', ['calendar' => $this->calendar->id]) . "?$query";
    }
}
