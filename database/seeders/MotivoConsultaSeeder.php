<?php

namespace Database\Seeders;

use App\Models\MotivoConsulta;
use Illuminate\Database\Seeder;

class MotivoConsultaSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            'CONTROL DE NIÑO SANO',
            'FIEBRE',
            'TOS Y/O RESFRIADO',
            'DIARREA',
            'VÓMITOS',
            'DOLOR DE OÍDO',
            'DOLOR ABDOMINAL',
            'ERUPCIÓN CUTÁNEA / SARPULLIDO',
            'DIFICULTAD RESPIRATORIA',
            'VACUNACIÓN',
            'CONSULTA DE SEGUIMIENTO',
            'TRAUMATISMO / LESIÓN',
            'ALERGIAS',
            'REVISIÓN DE RESULTADOS DE LABORATORIO',
            'OTRO',
        ];

        foreach ($motivos as $nombre) {
            MotivoConsulta::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
