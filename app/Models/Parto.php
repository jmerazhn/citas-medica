<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parto extends Model
{
    protected $fillable = [
        'patient_id',
        'fecha_parto',
        'lugar',
        'cesarea',
        'motivo_cesarea',
        'posicion',
        'parto_tipo',
        'apgar',
        'parto_gamma',
        'anestesia',
        'observaciones',
        'peso_rn',
        'talla_rn',
        'pc_rn',
        'ombligo_dias',
        'observaciones_rn',
    ];

    protected $casts = [
        'fecha_parto' => 'date',
        'cesarea'     => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function embarazo()
    {
        return $this->belongsTo(Embarazo::class);
    }
}
