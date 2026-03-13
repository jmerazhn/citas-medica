<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tablas_crecimiento', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['peso', 'talla', 'perimetro_cefalico', 'imc']);
            $table->enum('sexo', ['M', 'F']);
            $table->unsignedSmallInteger('edad_meses');
            $table->decimal('p3', 5, 2);
            $table->decimal('p10', 5, 2);
            $table->decimal('p25', 5, 2);
            $table->decimal('p50', 5, 2);
            $table->decimal('p75', 5, 2);
            $table->decimal('p90', 5, 2);
            $table->decimal('p97', 5, 2);
            $table->timestamps();

            $table->unique(['tipo', 'sexo', 'edad_meses']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tablas_crecimiento');
    }
};
