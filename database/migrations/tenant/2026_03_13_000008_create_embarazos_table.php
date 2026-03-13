<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('embarazos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('numero_embarazo')->nullable()->comment('Número de gesta');
            $table->date('fecha_ultima_menstruacion')->nullable()->comment('FUM');
            $table->date('fecha_probable_parto')->nullable()->comment('FPP');
            $table->unsignedTinyInteger('semanas_gestacion')->nullable()->comment('Al momento del registro');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('embarazos');
    }
};
