<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientPatologia extends Model
{
    protected $table = 'patient_patologias';

    protected $fillable = [
        'patient_id',
        'patologia_id',
        'fecha_diagnostico',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_diagnostico' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function patologia()
    {
        return $this->belongsTo(Patologia::class);
    }
}
