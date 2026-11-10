<?php

namespace App\Notifications;

use App\Models\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostulacionEstadoActualizadoNotification extends Notification
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
        $estado = $this->postulacion->estado;

        $mail = (new MailMessage)
            ->greeting('¡Hola, ' . $this->postulacion->estudiante . '!');

        if ($estado === 'Aceptado') {
            $mail->subject('¡Felicidades! Has sido Aceptado en ' . $tituloOportunidad)
                 ->line('Nos complace informarte que la organización **' . $ong . '** ha revisado y **ACEPTADO** tu postulación.')
                 ->line('**Convocatoria:** ' . $tituloOportunidad)
                 ->line('**Horas asignadas / a acreditar:** ' . $this->postulacion->horas_acumuladas . ' horas')
                 ->action('Ir a mi Panel de Voluntario', url('/home'))
                 ->line('Por favor, mantente atento a tu teléfono y correo para las coordinaciones del primer día.');
        } elseif ($estado === 'Rechazado') {
            $mail->subject('Actualización de tu postulación - VoluntMatch Cusco')
                 ->line('Te informamos que la convocatoria **' . $tituloOportunidad . '** ha concluido su proceso de selección y en esta ocasión los cupos fueron cubiertos.')
                 ->line('Te animamos a seguir postulando a otras iniciativas sociales disponibles en Cusco.')
                 ->action('Explorar Más Convocatorias', url('/oportunidades'));
        } else {
            $mail->subject('Actualización de estado en VoluntMatch Cusco')
                 ->line('El estado de tu postulación en **' . $tituloOportunidad . '** ha cambiado a: **' . $estado . '**.')
                 ->action('Ver mi Panel', url('/home'));
        }

        return $mail->salutation('Equipo de VoluntMatch Cusco');
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
            'nuevo_estado'   => $this->postulacion->estado,
        ];
    }
}
