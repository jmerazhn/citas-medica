<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->foreignId('motivo_consulta_id')->nullable()->constrained('motivos_consulta')->onDelete('set null');
            $table->string('motivo_detalle')->nullable();
            $table->decimal('peso', 5, 2)->nullable()->comment('kg');
            $table->decimal('talla', 5, 2)->nullable()->comment('cm');
            $table->decimal('temperatura', 4, 1)->nullable()->comment('°C');
            $table->unsignedSmallInteger('fc')->nullable()->comment('lat/min');
            $table->unsignedSmallInteger('fr')->nullable()->comment('resp/min');
            $table->unsignedSmallInteger('spo2')->nullable()->comment('%');
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
