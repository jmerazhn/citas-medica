<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parto extends Model
{
    protected $fillable = [
        'patient_id',
        'embarazo_id',
        'fecha_parto',
        'tipo_parto',
        'semanas_gestacion',
        'peso_rn',
        'talla_rn',
        'apgar_1',
        'apgar_5',
        'complicaciones',
        'notas',
    ];

    protected $casts = [
        'fecha_parto'  => 'date',
        'peso_rn'      => 'decimal:2',
        'talla_rn'     => 'decimal:1',
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
