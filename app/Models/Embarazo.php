<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embarazo extends Model
{
    protected $fillable = [
        'patient_id',
        'numero_embarazo',
        'fecha_ultima_menstruacion',
        'fecha_probable_parto',
        'semanas_gestacion',
        'notas',
    ];

    protected $casts = [
        'fecha_ultima_menstruacion' => 'date',
        'fecha_probable_parto'     => 'date',
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
