<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudioOrdenado extends Model
{
    protected $table = 'estudios_ordenados';

    protected $fillable = [
        'atencion_id',
        'estudio',
        'resultado',
    ];

    public function atencion()
    {
        return $this->belongsTo(Atencion::class);
    }
}
