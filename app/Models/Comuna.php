<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    protected $fillable = ['nombre', 'ciudad_id'];

    // Relaciones
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'comuna_id');
    }
}
