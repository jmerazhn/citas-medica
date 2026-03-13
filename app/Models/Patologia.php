<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patologia extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    public function pacientes()
    {
        return $this->hasMany(PatientPatologia::class);
    }
}
