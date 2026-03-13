<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'nombres',
        'apellidos',
        'sexo',
        'fecha_nacimiento',
        'madre',
        'padre',
        'domicilio',
        'ciudad',
        'telefono',
        'social_coverage_id',
        'blood_type_id',
        'notas_importantes',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function socialCoverage()
    {
        return $this->belongsTo(SocialCoverage::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class)->orderByDesc('fecha');
    }

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class)->orderByDesc('fecha_aplicacion');
    }

    public function patologias()
    {
        return $this->hasMany(PatientPatologia::class)->with('patologia');
    }

    public function embarazos()
    {
        return $this->hasMany(Embarazo::class)->orderByDesc('created_at');
    }

    public function partos()
    {
        return $this->hasMany(Parto::class)->orderByDesc('fecha_parto');
    }
}
