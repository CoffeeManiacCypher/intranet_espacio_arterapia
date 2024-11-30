<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'ciudades';

    protected $fillable = ['nombre'];

    // Relaciones
    public function comunas()
    {
        return $this->hasMany(Comuna::class, 'ciudad_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'ciudad_id');
    }
}
