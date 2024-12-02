<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'ciudades';

    // Especificar la clave primaria
    protected $primaryKey = 'ciudad_id';

    // Si la clave primaria no es un entero auto-incremental
    public $incrementing = true;

    // Si la clave primaria no es de tipo integer
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
    ];

    // Relaciones
    public function comunas()
    {
        return $this->hasMany(Comuna::class, 'ciudad_id', 'ciudad_id');
    }

    public function pacientes()
    {
        return $this->hasMany(Paciente::class, 'ciudad_id', 'ciudad_id');
    }
}
