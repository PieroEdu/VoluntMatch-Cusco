<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oportunidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ong_id');
            $table->string('ong_nombre');
            $table->string('titulo');
            $table->string('categoria');
            $table->string('distrito');
            $table->string('direccion_exacta')->nullable();
            $table->string('latitud')->default('-13.53195');
            $table->string('longitud')->default('-71.96746');
            $table->string('horario')->default('¡Es Flexible! Nos acomodamos a tu horario.');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->dateTime('fecha_cierre')->nullable();
            $table->integer('horas')->default(20);
            $table->integer('cupos')->default(10);
            $table->integer('cupos_disponibles')->default(10);
            $table->string('estado')->default('Abierta'); // Abierta, Cerrada
            $table->text('imagen')->nullable();
            $table->text('descripcion');
            $table->text('funciones')->nullable();
            $table->text('requisitos')->nullable();
            $table->timestamps();
        });

        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('oportunidad_id')->nullable();
            $table->unsignedBigInteger('ong_id')->nullable();
            $table->string('proyecto');
            $table->string('organizacion');
            $table->integer('horas')->default(20);
            $table->integer('horas_validadas')->default(0);
            $table->string('codigo_certificado')->nullable();

            // Ficha de postulación del estudiante
            $table->string('universidad')->nullable();
            $table->string('ciclo')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->string('disponibilidad')->nullable();
            $table->text('motivacion')->nullable();

            // Calificación y reseña de la ONG al finalizar
            $table->integer('calificacion')->nullable();
            $table->text('resena_ong')->nullable();

            $table->string('estado')->default('En revisión'); // En revisión, Aprobada, Rechazada, Completada, Baja
            $table->timestamps();
        });

        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postulacion_id');
            $table->date('fecha');
            $table->integer('horas_registradas');
            $table->string('actividad');
            $table->timestamps();
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tipo')->default('info');
            $table->string('titulo');
            $table->text('mensaje');
            $table->unsignedBigInteger('postulacion_id')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('asistencias');
        Schema::dropIfExists('postulaciones');
        Schema::dropIfExists('oportunidades');
    }
};