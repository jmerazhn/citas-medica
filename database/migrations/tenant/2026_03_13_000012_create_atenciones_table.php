<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->unique()->constrained()->cascadeOnDelete();

            // Síntomas y notas generales
            $table->text('sintomatologia')->nullable();
            $table->text('notas')->nullable();

            // Estado de crecimiento (texto libre)
            $table->string('peso')->nullable();
            $table->string('altura')->nullable();
            $table->string('pc')->nullable();
            $table->string('imc')->nullable();

            // Signos vitales (texto libre)
            $table->string('temperatura')->nullable();
            $table->string('fc')->nullable();
            $table->string('fr')->nullable();
            $table->string('presion_arterial')->nullable();

            // Diagnóstico y tratamiento
            $table->text('diagnostico_posible')->nullable();
            $table->text('diagnostico_confirmado')->nullable();
            $table->text('medicacion_indicada')->nullable();

            $table->timestamps();
        });

        Schema::create('estudios_ordenados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atencion_id')->constrained('atenciones')->cascadeOnDelete();
            $table->string('estudio');
            $table->text('resultado')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios_ordenados');
        Schema::dropIfExists('atenciones');
    }
};
