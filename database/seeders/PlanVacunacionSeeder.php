<?php

namespace Database\Seeders;

use App\Models\PlanVacunacion;
use Illuminate\Database\Seeder;

class PlanVacunacionSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            ['nombre' => 'ESQUEMA NACIONAL HONDURAS', 'descripcion' => 'ESQUEMA OFICIAL DE VACUNACIÓN DEL MINISTERIO DE SALUD DE HONDURAS.'],
            ['nombre' => 'OPS/OMS RECOMENDADO', 'descripcion' => 'ESQUEMA DE VACUNACIÓN RECOMENDADO POR LA ORGANIZACIÓN PANAMERICANA DE LA SALUD.'],
            ['nombre' => 'ESQUEMA PRIVADO', 'descripcion' => 'VACUNAS ADICIONALES RECOMENDADAS FUERA DEL ESQUEMA NACIONAL.'],
            ['nombre' => 'ESQUEMA DE RECUPERACIÓN', 'descripcion' => 'PARA PACIENTES CON VACUNAS ATRASADAS O ESQUEMA INCOMPLETO.'],
        ];

        foreach ($planes as $plan) {
            PlanVacunacion::firstOrCreate(['nombre' => $plan['nombre']], $plan);
        }
    }
}
