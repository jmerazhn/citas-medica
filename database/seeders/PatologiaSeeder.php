<?php

namespace Database\Seeders;

use App\Models\Patologia;
use Illuminate\Database\Seeder;

class PatologiaSeeder extends Seeder
{
    public function run(): void
    {
        $patologias = [
            ['nombre' => 'Asma bronquial', 'descripcion' => 'Enfermedad inflamatoria crónica de las vías respiratorias.'],
            ['nombre' => 'Rinitis alérgica', 'descripcion' => 'Inflamación de la mucosa nasal por reacción alérgica.'],
            ['nombre' => 'Dermatitis atópica', 'descripcion' => 'Inflamación crónica de la piel de origen alérgico.'],
            ['nombre' => 'Diabetes mellitus tipo 1', 'descripcion' => 'Déficit absoluto de insulina de origen autoinmune.'],
            ['nombre' => 'Epilepsia', 'descripcion' => 'Trastorno neurológico con episodios convulsivos recurrentes.'],
            ['nombre' => 'Hipotiroidismo congénito', 'descripcion' => 'Deficiencia de hormonas tiroideas desde el nacimiento.'],
            ['nombre' => 'Cardiopatía congénita', 'descripcion' => 'Malformación estructural del corazón presente desde el nacimiento.'],
            ['nombre' => 'Anemia', 'descripcion' => 'Reducción en el número de eritrocitos o en la hemoglobina.'],
            ['nombre' => 'Desnutrición', 'descripcion' => 'Estado nutricional deficiente por ingesta insuficiente o mala absorción.'],
            ['nombre' => 'Obesidad', 'descripcion' => 'Exceso de grasa corporal con IMC elevado para la edad.'],
            ['nombre' => 'Reflujo gastroesofágico', 'descripcion' => 'Retorno involuntario del contenido gástrico al esófago.'],
            ['nombre' => 'Otitis media recurrente', 'descripcion' => 'Infección del oído medio con episodios frecuentes.'],
            ['nombre' => 'Síndrome de Down', 'descripcion' => 'Trisomía 21 con retraso en el desarrollo.'],
            ['nombre' => 'Parálisis cerebral', 'descripcion' => 'Trastorno motor permanente por daño cerebral no progresivo.'],
            ['nombre' => 'Trastorno del espectro autista', 'descripcion' => 'Trastorno del neurodesarrollo que afecta la comunicación y conducta.'],
        ];

        foreach ($patologias as $item) {
            Patologia::firstOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
