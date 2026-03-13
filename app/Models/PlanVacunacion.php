<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanVacunacion extends Model
{
    protected $table = 'planes_vacunacion';

    protected $fillable = ['nombre', 'descripcion'];

    public function vacunas()
    {
        return $this->hasMany(Vacuna::class);
    }
}
