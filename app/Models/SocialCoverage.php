<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialCoverage extends Model
{
    protected $fillable = ['name'];

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = mb_strtoupper($value);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
