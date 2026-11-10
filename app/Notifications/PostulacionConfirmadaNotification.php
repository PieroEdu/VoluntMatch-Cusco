<?php

namespace App\Notifications;

use App\Models\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostulacionConfirmadaNotification extends Notification
{
    use Queueable;

    public Postulacion $postulacion;

    /**
     * Create a new notification instance.
     */
    public function __construct(Postulacion $postulacion)
    {
        $this->postulacion = $postulacion;
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
        $oportunidad = $this->postulacion->oportunidad;
        $tituloOportunidad = $oportunidad ? $oportunidad->titulo : 'Convocatoria de Voluntariado';
        $ong = $oportunidad ? $oportunidad->ong : 'Organización Social';
        $distrito = $oportunidad ? $oportunidad->distrito : 'Cusco';

        return (new MailMessage)
            ->subject('¡Postulación Registrada! - VoluntMatch Cusco')
            ->greeting('¡Hola, ' . $this->postulacion->estudiante . '!')
            ->line('Tu postulación para participar en la iniciativa social ha sido recibida correctamente.')
            ->line('**Convocatoria:** ' . $tituloOportunidad)
            ->line('**Organización:** ' . $ong)
            ->line('**Distrito / Ubicación:** ' . $distrito)
            ->line('**Estado actual:** ' . $this->postulacion->estado)
            ->action('Ver Estado en tu Panel', url('/home'))
            ->line('La organización revisará tu postulación y te notificaremos cuando sea aprobada.')
            ->salutation('Equipo de VoluntMatch Cusco');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'postulacion_id' => $this->postulacion->id,
            'oportunidad_id' => $this->postulacion->oportunidad_id,
            'estado'         => $this->postulacion->estado,
        ];
    }
}
