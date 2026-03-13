<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('embarazos', function (Blueprint $table) {
            $table->string('obstetra', 150)->nullable()->after('numero_embarazo');
            $table->boolean('diabetes')->default(false)->after('semanas_gestacion');
            $table->boolean('hipertension')->default(false)->after('diabetes');
            $table->boolean('traumatismo')->default(false)->after('hipertension');
            $table->boolean('infecciones')->default(false)->after('traumatismo');
            $table->boolean('asma')->default(false)->after('infecciones');
            $table->text('medicacion')->nullable()->after('asma');
            $table->text('observaciones')->nullable()->after('medicacion');
        });
    }

    public function down(): void
    {
        Schema::table('embarazos', function (Blueprint $table) {
            $table->dropColumn([
                'obstetra', 'diabetes', 'hipertension',
                'traumatismo', 'infecciones', 'asma',
                'medicacion', 'observaciones',
            ]);
        });
    }
};
