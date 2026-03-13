<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TablaCrecimiento extends Model
{
    protected $table = 'tablas_crecimiento';

    protected $fillable = [
        'tipo',
        'sexo',
        'edad_meses',
        'p3', 'p10', 'p25', 'p50', 'p75', 'p90', 'p97',
    ];

    protected $casts = [
        'edad_meses' => 'integer',
        'p3'  => 'decimal:2',
        'p10' => 'decimal:2',
        'p25' => 'decimal:2',
        'p50' => 'decimal:2',
        'p75' => 'decimal:2',
        'p90' => 'decimal:2',
        'p97' => 'decimal:2',
    ];

    public static array $tipos = [
        'peso'               => 'Peso (kg)',
        'talla'              => 'Talla (cm)',
        'perimetro_cefalico' => 'Perímetro Cefálico (cm)',
        'imc'                => 'IMC (kg/m²)',
    ];
}
