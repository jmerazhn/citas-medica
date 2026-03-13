<?php

namespace Database\Seeders;

use App\Models\MotivoConsulta;
use Illuminate\Database\Seeder;

class MotivoConsultaSeeder extends Seeder
{
    public function run(): void
    {
        $motivos = [
            'Control de niño sano',
            'Fiebre',
            'Tos y/o resfriado',
            'Diarrea',
            'Vómitos',
            'Dolor de oído',
            'Dolor abdominal',
            'Erupción cutánea / sarpullido',
            'Dificultad respiratoria',
            'Vacunación',
            'Consulta de seguimiento',
            'Traumatismo / lesión',
            'Alergias',
            'Revisión de resultados de laboratorio',
            'Otro',
        ];

        foreach ($motivos as $nombre) {
            MotivoConsulta::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
