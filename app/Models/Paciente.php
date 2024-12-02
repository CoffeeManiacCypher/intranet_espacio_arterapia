<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

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
        'verificado',
        'fecha_creacion',
    ];

    // Relaciones
    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id', 'ciudad_id');
    }

    // Métodos adicionales

    /**
     * Verifica el estado del paciente basado en la completitud de sus datos.
     */
    public function verificarEstado()
    {
        if (!empty($this->rut) && !empty($this->nombres) && !empty($this->apellidos)) {
            $this->verificado = 'verificado';
        } else {
            $this->verificado = 'pendiente';
        }
    }

    /**
     * Calcula la edad y la guarda en la columna correspondiente si la fecha de nacimiento es válida.
     */
    public function calcularEdad()
    {
        if ($this->fecha_nacimiento) {
            try {
                // Convertir fecha de nacimiento al formato correcto
                $fechaNacimiento = Carbon::parse($this->fecha_nacimiento);
                
                // Validar si la fecha es válida y en el pasado
                if ($fechaNacimiento->isPast()) {
                    // Calcular la edad correctamente
                    $this->edad = $fechaNacimiento->diffInYears(Carbon::now());
                } else {
                    $this->edad = null; // Asignar null si la fecha es inválida o futura
                }
            } catch (\Exception $e) {
                $this->edad = null; // Manejar errores asignando null
            }
        } else {
            $this->edad = null; // Asignar null si no hay fecha de nacimiento
        }
    }
    

    /**
     * Mutator para asegurarse de que fecha_nacimiento se almacene como una instancia de Carbon.
     */
    public function setFechaNacimientoAttribute($value)
    {
        try {
            $this->attributes['fecha_nacimiento'] = Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->attributes['fecha_nacimiento'] = null; // Si ocurre un error, manejarlo asignando null
        }
    }

    /**
     * Boot para calcular edad automáticamente al guardar o actualizar un paciente.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($paciente) {
            // Calcular edad y verificar estado antes de guardar el paciente
            $paciente->calcularEdad();
            $paciente->verificarEstado();
        });
    }
}
