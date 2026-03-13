<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Siembra la base de datos de cada TENANT (consultorio).
     * Ejecutar con: php artisan tenants:seed --class=TenantDatabaseSeeder
     */
    public function run(): void
    {
        $this->call([RoleSeeder::class]);

        // Limpiar caché de Spatie para que UserSeeder encuentre los roles recién creados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            UserSeeder::class,
            BloodTypeSeeder::class,
            SocialCoverageSeeder::class,
            MotivoConsultaSeeder::class,
            PlanVacunacionSeeder::class,
            PatologiaSeeder::class,
            TablaCrecimientoSeeder::class,
        ]);
    }
}
