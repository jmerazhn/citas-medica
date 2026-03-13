<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $slug   = 'drgarcia';
        $dbName = config('tenancy.database.prefix') . $slug;

        // migrate:fresh borra las tablas centrales pero no las DBs de tenants.
        // Eliminamos la DB si ya existe para que TenantCreated pueda recrearla limpia.
        \DB::statement("DROP DATABASE IF EXISTS `{$dbName}`");

        Tenant::create([
            'id'     => $slug,
            'nombre' => 'Consultorio Pediátrico García',
            'email'  => 'drgarcia@citas.hn',
            'activo' => true,
        ]);
    }
}
