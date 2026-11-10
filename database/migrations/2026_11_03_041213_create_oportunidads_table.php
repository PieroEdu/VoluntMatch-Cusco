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
        Schema::create('oportunidads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizacion_id')->nullable()->constrained('organizacions')->nullOnDelete();
            $table->string('titulo');
            $table->string('ong');
            $table->string('distrito')->default('Cusco');
            $table->string('categoria')->default('Educación');
            $table->string('clase_insignia')->default('badge-educacion');
            $table->string('modalidad')->default('Presencial');
            $table->string('horario')->default('Flexible');
            $table->integer('cupos')->default(5);
            $table->integer('cupos_restantes')->default(5);
            $table->string('imagen')->nullable();
            $table->text('descripcion');
            $table->json('habilidades')->nullable();
            $table->boolean('destacada')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oportunidads');
    }
};
