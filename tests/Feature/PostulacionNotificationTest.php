<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Oportunidad;
use App\Models\Postulacion;
use App\Notifications\PostulacionConfirmadaNotification;
use App\Notifications\PostulacionEstadoActualizadoNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class PostulacionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmation_notification_is_sent_when_volunteer_applies()
    {
        Notification::fake();

        $oportunidad = Oportunidad::create([
            'titulo' => 'Tutorías Escolares en Wanchaq',
            'ong' => 'Azul Wasi',
            'distrito' => 'Wanchaq',
            'categoria' => 'Educación',
            'clase_insignia' => 'badge-educacion',
            'modalidad' => 'Presencial',
            'horario' => 'Sábados',
            'cupos' => 5,
            'cupos_restantes' => 5,
            'descripcion' => 'Apoyo escolar para niños.',
        ]);

        $payload = [
            'oportunidad_id'     => $oportunidad->id,
            'estudiante'         => 'Lucía Morales',
            'correo'             => 'lucia.morales@ejemplo.com',
            'telefono'           => '984123456',
            'carrera'            => 'Psicología',
            'universidad'        => 'Universidad Continental',
            'mensaje_motivacion' => 'Quiero aportar a la comunidad cusqueña.',
        ];

        $response = $this->post(route('postulaciones.store'), $payload);

        $response->assertSessionHasNoErrors();

        // Verificar que se haya enviado la notificación a la dirección de correo
        Notification::assertSentOnDemand(
            PostulacionConfirmadaNotification::class,
            function (PostulacionConfirmadaNotification $notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'lucia.morales@ejemplo.com'
                    && $notification->postulacion->estudiante === 'Lucía Morales';
            }
        );
    }

    public function test_status_notification_is_sent_when_ong_updates_state()
    {
        Notification::fake();

        $user = User::factory()->create();

        $oportunidad = Oportunidad::create([
            'titulo' => 'Digitalización de Archivos',
            'ong' => 'Asociación Verde Cusco',
            'distrito' => 'Cusco',
            'categoria' => 'Sistemas',
            'clase_insignia' => 'badge-sistemas',
            'modalidad' => 'Híbrido',
            'horario' => 'Flexible',
            'cupos' => 4,
            'cupos_restantes' => 4,
            'descripcion' => 'Organización digital.',
        ]);

        $postulacion = Postulacion::create([
            'oportunidad_id'   => $oportunidad->id,
            'estudiante'       => 'Carlos Quispe',
            'correo'           => 'carlos.quispe@ejemplo.com',
            'estado'           => 'Pendiente',
            'horas_acumuladas' => 0,
            'fecha'            => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)
                         ->post(route('postulaciones.estado', ['id' => $postulacion->id]), [
                             'estado' => 'Aceptado',
                             'horas'  => 12,
                         ]);

        $response->assertSessionHasNoErrors();

        // Verificar que la notificación de estado actualizado fue enviada
        Notification::assertSentOnDemand(
            PostulacionEstadoActualizadoNotification::class,
            function (PostulacionEstadoActualizadoNotification $notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'carlos.quispe@ejemplo.com'
                    && $notification->postulacion->estado === 'Aceptado'
                    && $notification->postulacion->horas_acumuladas === 12;
            }
        );
    }
}
