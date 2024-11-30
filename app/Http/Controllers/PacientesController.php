<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Ciudad;
use App\Models\Comuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shuchkin\SimpleXLSX;


class PacientesController extends Controller
{
    /**
     * Muestra la lista de pacientes junto con las comunas y ciudades relacionadas.
     */
    public function index()
    {
        $pacientes = Paciente::with(['comuna', 'ciudad'])->get(); // Relación con comunas y ciudades
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
     * Almacena un nuevo paciente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rut' => 'nullable|max:12|unique:pacientes,rut', // Rut puede ser nulo para casos incompletos
            'nombres' => 'nullable|max:255',
            'apellidos' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'telefono' => 'nullable|max:15',
            'telefono_secundario' => 'nullable|max:15',
            'direccion' => 'nullable|max:255',
            'comuna_id' => 'nullable|exists:comunas,id',
            'ciudad_id' => 'nullable|exists:ciudades,id',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'required|in:Femenino,Masculino,Otro',
        ]);

        // Calcula la edad si se proporciona la fecha de nacimiento
        $validated['edad'] = $validated['fecha_nacimiento']
            ? now()->year - date('Y', strtotime($validated['fecha_nacimiento']))
            : null;

        // Determina el estado de verificación
        $validated['verificado'] = $this->verificarEstadoPaciente($validated);

        // Genera un ID único si falta el ID
        $validated['id'] = Paciente::max('id') + 1;

        // Crea el paciente
        Paciente::create($validated);

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    /**
     * Verifica el estado del paciente basado en la completitud de sus datos.
     */
    private function verificarEstadoPaciente(array $data): string
    {
        if (empty($data['rut']) || empty($data['nombres']) || empty($data['apellidos'])) {
            return 'pendiente'; // Datos incompletos
        }
        return 'verificado'; // Datos completos
    }

    /**
     * Filtra los pacientes con base en los parámetros enviados.
     */
    public function filtrar(Request $request)
    {
        $query = Paciente::query();

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        if ($request->filled('estado')) {
            $query->where('verificado', $request->estado);
        }

        if ($request->filled('comuna_id')) {
            $query->where('comuna_id', $request->comuna_id);
        }

        if ($request->filled('ciudad_id')) {
            $query->where('ciudad_id', $request->ciudad_id);
        }

        $pacientes = $query->get();
        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Actualiza los datos de los pacientes existentes para manejar los campos faltantes.
     */
    public function actualizarPacientes()
    {
        $pacientes = Paciente::all();

        foreach ($pacientes as $paciente) {
            // Generar un ID único para los pacientes sin ID
            if (empty($paciente->id)) {
                $paciente->id = Paciente::max('id') + 1;
            }

            // Determina si el paciente está verificado o pendiente
            $paciente->verificado = $this->verificarEstadoPaciente($paciente->toArray());

            // Guarda los cambios
            $paciente->save();
        }

        return redirect()->route('pacientes.index')->with('success', 'Pacientes actualizados correctamente.');
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);
    
        if ($xlsx = SimpleXLSX::parse($request->file('archivo'))) {
            $rows = $xlsx->rows();
            unset($rows[0]); // Eliminar encabezado si está presente
    
            foreach ($rows as $row) {
                Paciente::create([
                    'nombres' => $row[0] ?? null,
                    'apellidos' => $row[1] ?? null,
                    'telefono' => $row[2] ?? null,
                    'verificado' => $row[3] ?? 'pendiente',
                    'fecha_creacion' => now(),
                ]);
            }
    
            return redirect()->route('pacientes.index')->with('success', 'Pacientes importados correctamente.');
        }
    
        return redirect()->route('pacientes.index')->with('error', 'Error al procesar el archivo.');
    }
    
    public function exportar()
    {
        $filename = 'pacientes_' . now()->format('Y_m_d_His') . '.csv';
        $pacientes = DB::table('pacientes')->get();

        // Establecer encabezados para la descarga del archivo
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($pacientes) {
            $file = fopen('php://output', 'w');
            
            // Escribir encabezados en el CSV
            fputcsv($file, ['ID', 'Nombres', 'Apellidos', 'Teléfono', 'Email', 'Estado', 'Fecha Registro']);

            // Escribir datos
            foreach ($pacientes as $paciente) {
                fputcsv($file, [
                    $paciente->id,
                    $paciente->nombres,
                    $paciente->apellidos,
                    $paciente->telefono,
                    $paciente->email,
                    ucfirst($paciente->verificado),
                    $paciente->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
