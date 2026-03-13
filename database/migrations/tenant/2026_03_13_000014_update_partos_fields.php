<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partos', function (Blueprint $table) {
            $table->string('lugar', 200)->nullable()->after('fecha_parto');
            $table->boolean('cesarea')->default(false)->after('lugar');
            $table->text('motivo_cesarea')->nullable()->after('cesarea');
            $table->string('posicion', 20)->nullable()->after('motivo_cesarea')->comment('cefalica|podalica');
            $table->string('parto_tipo', 20)->nullable()->after('posicion')->comment('eutocico|distocico');
            $table->text('apgar')->nullable()->after('parto_tipo')->comment('Texto libre');
            $table->text('parto_gamma')->nullable()->after('apgar');
            $table->string('anestesia', 20)->nullable()->after('parto_gamma')->comment('no|raquidea|peridural|total');
            $table->text('observaciones')->nullable()->after('anestesia');
            $table->string('pc_rn', 20)->nullable()->after('talla_rn')->comment('Perímetro cefálico RN');
            $table->string('ombligo_dias', 50)->nullable()->after('pc_rn')->comment('Caída del ombligo en días');
            $table->text('observaciones_rn')->nullable()->after('ombligo_dias');
        });
    }

    public function down(): void
    {
        Schema::table('partos', function (Blueprint $table) {
            $table->dropColumn([
                'lugar', 'cesarea', 'motivo_cesarea', 'posicion', 'parto_tipo',
                'apgar', 'parto_gamma', 'anestesia', 'observaciones',
                'pc_rn', 'ombligo_dias', 'observaciones_rn',
            ]);
        });
    }
};
