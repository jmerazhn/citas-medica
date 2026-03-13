<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('motivo_consulta_id')
                ->nullable()
                ->after('status')
                ->constrained('motivos_consulta')
                ->nullOnDelete();

            $table->string('reason')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('motivo_consulta_id');
            $table->string('reason')->nullable(false)->change();
        });
    }
};
