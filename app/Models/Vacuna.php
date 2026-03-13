<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacuna extends Model
{
    protected $fillable = [
        'patient_id',
        'plan_vacunacion_id',
        'vacuna',
        'fecha_aplicacion',
        'dosis',
        'lote',
        'notas',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function planVacunacion()
    {
        return $this->belongsTo(PlanVacunacion::class);
    }
}
