<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('embarazo_id')->nullable()->constrained('embarazos')->onDelete('set null');
            $table->date('fecha_parto');
            $table->enum('tipo_parto', ['vaginal', 'cesarea']);
            $table->unsignedTinyInteger('semanas_gestacion')->nullable();
            $table->decimal('peso_rn', 4, 2)->nullable()->comment('kg');
            $table->decimal('talla_rn', 4, 1)->nullable()->comment('cm');
            $table->unsignedTinyInteger('apgar_1')->nullable()->comment('0-10');
            $table->unsignedTinyInteger('apgar_5')->nullable()->comment('0-10');
            $table->text('complicaciones')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partos');
    }
};
