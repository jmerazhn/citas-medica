<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Eliminar columnas anteriores
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'allergies',
                'chronic_conditions',
                'surgical_history',
                'family_medical_history',
                'observations',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
            ]);

            // Agregar nuevos campos personales
            $table->string('nombres')->after('id');
            $table->string('apellidos')->after('nombres');
            $table->enum('sexo', ['M', 'F'])->nullable()->after('apellidos');
            $table->date('fecha_nacimiento')->nullable()->after('sexo');
            $table->string('madre')->nullable()->after('fecha_nacimiento');
            $table->string('padre')->nullable()->after('madre');
            $table->string('domicilio')->nullable()->after('padre');
            $table->string('ciudad')->nullable()->after('domicilio');
            $table->string('telefono')->nullable()->after('ciudad');
            $table->foreignId('social_coverage_id')->nullable()->after('telefono')
                ->constrained()->onDelete('set null');
            $table->text('notas_importantes')->nullable()->after('blood_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['social_coverage_id']);
            $table->dropColumn([
                'nombres', 'apellidos', 'sexo', 'fecha_nacimiento',
                'madre', 'padre', 'domicilio', 'ciudad', 'telefono',
                'social_coverage_id', 'notas_importantes',
            ]);

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('allergies')->nullable();
            $table->string('chronic_conditions')->nullable();
            $table->string('surgical_history')->nullable();
            $table->string('family_medical_history')->nullable();
            $table->string('observations')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
        });
    }
};
