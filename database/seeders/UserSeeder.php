<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de Spatie para que encuentre los roles recién creados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = User::firstOrCreate(
            ['email' => 'josuemeraz7@gmail.com'],
            [
                'name'     => 'Josue Meraz',
                'password' => bcrypt('password'),
            ]
        );

        if (!$user->hasRole('Doctor')) {
            $user->assignRole('Doctor');
        }
    }
}
