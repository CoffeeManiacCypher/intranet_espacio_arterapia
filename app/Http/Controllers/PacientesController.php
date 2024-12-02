<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Ciudad;
use App\Models\Comuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacientesController extends Controller
{
    /**
     * Muestra la lista de pacientes junto con las comunas y ciudades relacionadas.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $query = Paciente::with(['comuna', 'ciudad']);

        // Filtros
        if ($request->filled('nombre')) {
            $query->where('nombres', 'like', '%' . $request->nombre . '%')
                  ->orWhere('apellidos', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('rut')) {
            $query->where('rut', 'like', '%' . $request->rut . '%');
        }

        if ($request->filled('correo')) {
            $query->where('email', 'like', '%' . $request->correo . '%');
        }

        if ($request->filled('verificado')) {
            $query->where('verificado', $request->verificado);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('telefono')) {
            $query->where('telefono', 'like', '%' . $request->telefono . '%');
        }

        if ($request->filled('fecha_registro')) {
            $query->whereDate('created_at', $request->fecha_registro);
        }
        if ($request->filled('ciudad')) {
            $query->where('ciudad_id', $request->ciudad);
        }
        if ($request->filled('comuna')) {
            $query->where('comuna_id', $request->comuna);
        }
        
        $pacientes = $query->paginate($perPage);

        // Retornar JSON si es una solicitud AJAX
        if ($request->ajax()) {
            return response()->json($pacientes);
        }
        $ciudades = Ciudad::all();
        $comunas = Comuna::all();
    
        return view('pacientes.index', compact('pacientes', 'ciudades', 'comunas'));
    }

    /**
     * Muestra el formulario para añadir un nuevo paciente.
     */
    public function create()
    {
        $ciudades = Ciudad::all();
        $comunas = Comuna::all();

        return view('pacientes.create', compact('ciudades', 'comunas'));
    }

    /**
     * Valida los datos antes de guardar o registrar un paciente.
     */
    public function validarDatos(Request $request)
    {
        $email = $request->input('email');
        $rut = $request->input('rut');

        if ($email && Paciente::where('email', $email)->exists()) {
            return response()->json([
                'valid' => false,
                'message' => 'El email ingresado ya está registrado.',
            ], 422);
        }

        if ($rut && Paciente::where('rut', $rut)->exists()) {
            return response()->json([
                'valid' => false,
                'message' => 'El RUT ingresado ya está registrado.',
            ], 422);
        }

        return response()->json(['valid' => true]);
    }

    /**
     * Almacena un nuevo paciente.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validación de datos
            $validated = $request->validate([
                'rut' => 'required|max:12|unique:pacientes,rut',
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'email' => 'required|email|max:255|unique:pacientes,email',
                'telefono' => 'nullable|max:15',
                'telefono_secundario' => 'nullable|max:15',
                'direccion' => 'nullable|max:255',
                'comuna_id' => 'nullable|exists:comunas,comuna_id',
                'ciudad_id' => 'nullable|exists:ciudades,ciudad_id',
                'fecha_nacimiento' => 'required|date|before:today', // Validar en formato 'Y-m-d'
                'genero' => 'required|in:Femenino,Masculino,Otro',
            ]);

            // Crear y guardar el paciente
            $paciente = new Paciente($validated);
            $paciente->save();

            DB::commit(); // Confirmar transacción

            return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir los cambios si ocurre un error
            return back()->withErrors(['error' => 'Error al guardar el paciente: ' . $e->getMessage()]);
        }
    }

    /**
     * Verifica el estado del paciente basado en la completitud de sus datos.
     */
    private function verificarEstadoPaciente(array $data): string
    {
        if (empty($data['rut']) || empty($data['nombres']) || empty($data['apellidos'])) {
            return 'pendiente';
        }
        return 'verificado';
    }

    /**
     * Actualiza los datos de los pacientes existentes.
     */
    public function actualizarPacientes()
    {
        $pacientes = Paciente::all();

        foreach ($pacientes as $paciente) {
            $paciente->verificado = $this->verificarEstadoPaciente($paciente->toArray());
            $paciente->save();
        }

        return redirect()->route('pacientes.index')->with('success', 'Pacientes actualizados correctamente.');
    }

    public function setRutAttribute($value)
    {
        $this->attributes['rut'] = preg_replace('/[^0-9kK]/', '', strtolower($value)); // Eliminar puntos y convertir a minúsculas
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rut' => 'required|max:12',
            'nombres' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|max:15',
            'direccion' => 'nullable|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|in:Femenino,Masculino,Otro',
        ]);
    
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado.'], 404);
        }
    
        $paciente->update($validated);
    
        return response()->json(['message' => 'Paciente actualizado con éxito.']);
    }

    public function show($id)
    {
        $paciente = Paciente::with(['comuna', 'ciudad'])->find($id);
        if (!$paciente) {
            return response()->json(['error' => 'Paciente no encontrado.'], 404);
        }
        return response()->json($paciente);
    }

    public function actualizarEdad($id)
    {
        $paciente = Paciente::find($id);
        if (!$paciente) {
            return response()->json(['message' => 'Paciente no encontrado.'], 404);
        }
    
        $paciente->edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        $paciente->save();
    
        return response()->json(['message' => 'Edad actualizada correctamente.']);
    }

    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        if ($paciente) {
            $paciente->delete();
            return response()->json(['message' => 'Paciente eliminado con éxito.']);
        }
    
        return response()->json(['message' => 'Paciente no encontrado.'], 404);
    }

}
