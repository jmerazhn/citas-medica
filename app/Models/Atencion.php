<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    protected $table = 'atenciones';

    protected $fillable = [
        'appointment_id',
        'sintomatologia',
        'notas',
        'peso',
        'altura',
        'pc',
        'imc',
        'temperatura',
        'fc',
        'fr',
        'presion_arterial',
        'diagnostico_posible',
        'diagnostico_confirmado',
        'medicacion_indicada',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function estudiosOrdenados()
    {
        return $this->hasMany(EstudioOrdenado::class);
    }
}
