<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Siembra la base de datos CENTRAL (tenants, super_admins).
     * Para sembrar un tenant individual: php artisan tenants:seed
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            TenantSeeder::class,
        ]);
    }
}
