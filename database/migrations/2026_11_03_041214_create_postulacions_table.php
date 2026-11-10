<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postulacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidad_id')->constrained('oportunidads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estudiante');
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->string('carrera')->nullable();
            $table->string('universidad')->default('Universidad Continental (Sede Cusco)');
            $table->text('mensaje_motivacion')->nullable();
            $table->string('estado')->default('Pendiente'); // Pendiente, Aceptado, Rechazado
            $table->integer('horas_acumuladas')->default(0);
            $table->date('fecha')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulacions');
    }
};
