<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    // Atributos que se pueden asignar masivamente
    protected $fillable = [
        'rut',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'telefono_secundario',
        'direccion',
        'comuna_id',
        'ciudad_id',
        'fecha_nacimiento',
        'edad',
        'genero',
        'verificado', // Nuevo campo
        'fecha_creacion',
    ];

    // Relaciones
    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    // Métodos adicionales
    /**
     * Actualiza automáticamente el estado del campo 'verificado' según los datos críticos.
     */
    public function verificarEstado()
    {
        if ($this->rut && $this->nombres && $this->apellidos) {
            $this->verificado = 'verificado';
        } else {
            $this->verificado = 'pendiente';
        }

        // Guardar cambios automáticamente si se está trabajando con un registro cargado
        if ($this->exists) {
            $this->save();
        }
    }

    /**
     * Calcula la edad automáticamente basado en la fecha de nacimiento.
     */
    public function calcularEdad()
    {
        if ($this->fecha_nacimiento) {
            $this->edad = now()->diffInYears($this->fecha_nacimiento);

            // Guardar cambios automáticamente si se está trabajando con un registro cargado
            if ($this->exists) {
                $this->save();
            }
        }
    }
}
