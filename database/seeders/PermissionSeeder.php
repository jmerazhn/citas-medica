<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Definir permisos agrupados por módulo
        $permissions = [
            // Gestión
            'ver-roles',
            'gestionar-roles',
            'ver-usuarios',
            'gestionar-usuarios',
            // Pacientes
            'ver-pacientes',
            'gestionar-pacientes',
            // Expediente clínico
            'ver-expediente',
            'gestionar-expediente',
            // Citas
            'ver-citas',
            'gestionar-citas',
            'confirmar-citas',
            'completar-citas',
            'cancelar-citas',
            // Horarios de doctores
            'gestionar-horarios',
            // Catálogos
            'ver-catalogos',
            'gestionar-catalogos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Asignar permisos a roles
        $rolePermissions = [
            'Administrador' => $permissions, // todo

            'Recepcionista' => [
                'ver-usuarios',
                'ver-pacientes',
                'gestionar-pacientes',
                'ver-expediente',
                'ver-citas',
                'gestionar-citas',
                'confirmar-citas',
                'cancelar-citas',
                'ver-catalogos',
            ],

            'Doctor' => [
                'ver-pacientes',
                'ver-expediente',
                'gestionar-expediente',
                'ver-citas',
                'completar-citas',
                'cancelar-citas',
                'gestionar-horarios',
                'ver-catalogos',
            ],

            'Paciente' => [], // Sin acceso al panel
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($perms);
            }
        }
    }
}
