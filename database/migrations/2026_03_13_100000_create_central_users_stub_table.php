<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla `users` vacía en la BD central.
 *
 * La BD central no tiene usuarios reales (están en cada tenant).
 * Sin embargo, DatabaseSessionHandler llama a Auth::guard('web')->id()
 * al guardar la sesión, lo que ejecuta `select * from users where id = ?`.
 * Sin esta tabla la consulta lanza una excepción 500.
 * Con la tabla vacía devuelve null y todo continúa sin error.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
