<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embarazo extends Model
{
    protected $fillable = [
        'patient_id',
        'numero_embarazo',
        'obstetra',
        'semanas_gestacion',
        'diabetes',
        'hipertension',
        'traumatismo',
        'infecciones',
        'asma',
        'medicacion',
        'observaciones',
    ];

    protected $casts = [
        'diabetes'     => 'boolean',
        'hipertension' => 'boolean',
        'traumatismo'  => 'boolean',
        'infecciones'  => 'boolean',
        'asma'         => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function partos()
    {
        return $this->hasMany(Parto::class);
    }
}
