<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoConsulta extends Model
{
    protected $table = 'motivos_consulta';

    protected $fillable = ['nombre'];

    public function setNombreAttribute(string $value): void
    {
        $this->attributes['nombre'] = mb_strtoupper($value);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}
