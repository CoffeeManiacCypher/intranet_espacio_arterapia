<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    use HasFactory;

    protected $table = 'comunas';

    // Especificar la clave primaria
    protected $primaryKey = 'comuna_id';

    // Si la clave primaria no es un entero auto-incremental
    public $incrementing = true;

    // Si la clave primaria no es de tipo integer
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'ciudad_id',
    ];

    // Relaciones
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }
}
