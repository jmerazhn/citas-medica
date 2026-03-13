<?php

namespace Database\Seeders;

use App\Models\PlanVacunacion;
use Illuminate\Database\Seeder;

class PlanVacunacionSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            ['nombre' => 'Esquema Nacional Honduras', 'descripcion' => 'Esquema oficial de vacunación del Ministerio de Salud de Honduras.'],
            ['nombre' => 'OPS/OMS Recomendado', 'descripcion' => 'Esquema de vacunación recomendado por la Organización Panamericana de la Salud.'],
            ['nombre' => 'Esquema Privado', 'descripcion' => 'Vacunas adicionales recomendadas fuera del esquema nacional.'],
            ['nombre' => 'Esquema de Recuperación', 'descripcion' => 'Para pacientes con vacunas atrasadas o esquema incompleto.'],
        ];

        foreach ($planes as $plan) {
            PlanVacunacion::firstOrCreate(['nombre' => $plan['nombre']], $plan);
        }
    }
}
