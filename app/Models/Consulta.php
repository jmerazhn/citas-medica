<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = [
        'patient_id',
        'fecha',
        'motivo_consulta_id',
        'motivo_detalle',
        'peso',
        'talla',
        'temperatura',
        'fc',
        'fr',
        'spo2',
        'diagnostico',
        'tratamiento',
        'notas',
    ];

    protected $casts = [
        'fecha'       => 'date',
        'peso'        => 'decimal:2',
        'talla'       => 'decimal:2',
        'temperatura' => 'decimal:1',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function motivoConsulta()
    {
        return $this->belongsTo(MotivoConsulta::class);
    }
}
