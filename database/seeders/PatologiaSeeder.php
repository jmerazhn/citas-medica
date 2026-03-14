<?php

namespace Database\Seeders;

use App\Models\Patologia;
use Illuminate\Database\Seeder;

class PatologiaSeeder extends Seeder
{
    public function run(): void
    {
        $patologias = [
            ['nombre' => 'ASMA BRONQUIAL', 'descripcion' => 'ENFERMEDAD INFLAMATORIA CRÓNICA DE LAS VÍAS RESPIRATORIAS.'],
            ['nombre' => 'RINITIS ALÉRGICA', 'descripcion' => 'INFLAMACIÓN DE LA MUCOSA NASAL POR REACCIÓN ALÉRGICA.'],
            ['nombre' => 'DERMATITIS ATÓPICA', 'descripcion' => 'INFLAMACIÓN CRÓNICA DE LA PIEL DE ORIGEN ALÉRGICO.'],
            ['nombre' => 'DIABETES MELLITUS TIPO 1', 'descripcion' => 'DÉFICIT ABSOLUTO DE INSULINA DE ORIGEN AUTOINMUNE.'],
            ['nombre' => 'EPILEPSIA', 'descripcion' => 'TRASTORNO NEUROLÓGICO CON EPISODIOS CONVULSIVOS RECURRENTES.'],
            ['nombre' => 'HIPOTIROIDISMO CONGÉNITO', 'descripcion' => 'DEFICIENCIA DE HORMONAS TIROIDEAS DESDE EL NACIMIENTO.'],
            ['nombre' => 'CARDIOPATÍA CONGÉNITA', 'descripcion' => 'MALFORMACIÓN ESTRUCTURAL DEL CORAZÓN PRESENTE DESDE EL NACIMIENTO.'],
            ['nombre' => 'ANEMIA', 'descripcion' => 'REDUCCIÓN EN EL NÚMERO DE ERITROCITOS O EN LA HEMOGLOBINA.'],
            ['nombre' => 'DESNUTRICIÓN', 'descripcion' => 'ESTADO NUTRICIONAL DEFICIENTE POR INGESTA INSUFICIENTE O MALA ABSORCIÓN.'],
            ['nombre' => 'OBESIDAD', 'descripcion' => 'EXCESO DE GRASA CORPORAL CON IMC ELEVADO PARA LA EDAD.'],
            ['nombre' => 'REFLUJO GASTROESOFÁGICO', 'descripcion' => 'RETORNO INVOLUNTARIO DEL CONTENIDO GÁSTRICO AL ESÓFAGO.'],
            ['nombre' => 'OTITIS MEDIA RECURRENTE', 'descripcion' => 'INFECCIÓN DEL OÍDO MEDIO CON EPISODIOS FRECUENTES.'],
            ['nombre' => 'SÍNDROME DE DOWN', 'descripcion' => 'TRISOMÍA 21 CON RETRASO EN EL DESARROLLO.'],
            ['nombre' => 'PARÁLISIS CEREBRAL', 'descripcion' => 'TRASTORNO MOTOR PERMANENTE POR DAÑO CEREBRAL NO PROGRESIVO.'],
            ['nombre' => 'TRASTORNO DEL ESPECTRO AUTISTA', 'descripcion' => 'TRASTORNO DEL NEURODESARROLLO QUE AFECTA LA COMUNICACIÓN Y CONDUCTA.'],
        ];

        foreach ($patologias as $item) {
            Patologia::firstOrCreate(['nombre' => $item['nombre']], $item);
        }
    }
}
