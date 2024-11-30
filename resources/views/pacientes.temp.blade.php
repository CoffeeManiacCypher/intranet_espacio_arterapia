@extends('layouts.module')

@section('title', 'Pacientes')

@section('content')
@vite(['resources/css/pacientes.css', 'resources/js/pacientes.js'])
<div class="module-container">
    <!-- Contenedor de Filtros -->
    <div class="filters-container">
        <h2>Filtros</h2>
        <div class="filter-group">
            <label for="filter-country">País</label>
            <select id="filter-country" class="form-select">
                <option value="">Todos</option>
                <option value="Chile">Chile</option>
                <option value="Argentina">Argentina</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="filter-period">Período</label>
            <input type="month" id="filter-period" class="form-control">
        </div>
        <button class="btn-primary" id="apply-filters">Aplicar filtros</button>
    </div>

    <!-- Contenedor central (Tabla) -->
    <div class="table-container">
        <div class="actions-row">

        <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#registerPatientModal">
            Registrar Paciente
        </button>

            <button class="btn-secondary">Importar registros</button>
            <button class="btn-secondary">Exportar registros</button>
        </div>
        <div class="selected-actions-row">
            <button class="btn-primary">Editar</button>
            <button class="btn-danger">Eliminar</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>RUT</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Género</th>
                    <th>Fecha Nacimiento</th>
                    <th>Edad</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pacientes as $paciente)
                <tr>
                    <td>{{ $paciente->rut }}</td>
                    <td>{{ $paciente->nombres }}</td>
                    <td>{{ $paciente->apellidos }}</td>
                    <td>{{ $paciente->telefono }}</td>
                    <td>{{ $paciente->email }}</td>
                    <td>{{ $paciente->genero }}</td>
                    <td>{{ $paciente->fecha_nacimiento }}</td>
                    <td>{{ $paciente->edad }}</td>
                    <td>{{ $paciente->fecha_registro }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Contenedor de estadísticas -->
    <div class="analysis-container">
        <h2>Estadísticas</h2>
        <div class="chart-container">
            <canvas id="patient-chart"></canvas>
        </div>
        <div class="total-patients">
            <h3>Total Pacientes</h3>
            <p>3,000 registrados</p>
        </div>
    </div>
</div>

<!-- Contenedor para alertas -->
<div id="alert-container"></div>

<!-- Modal para crear la tabla -->
@if (!Schema::hasTable('pacientes'))
    <div class="alert alert-warning">
        La tabla <strong>pacientes</strong> no existe.
    </div>
@else
    <div class="alert alert-success">
        La tabla <strong>pacientes</strong> ya existe.
    </div>
@endif

@if (!Schema::hasTable('pacientes'))
<div class="modal fade" id="createTableModal" tabindex="-1" aria-labelledby="createTableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTableModalLabel">Tabla no encontrada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>No se encontró la tabla <strong>pacientes</strong> en la base de datos. ¿Desea crearla ahora?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('crear-tabla-pacientes') }}">
                    @csrf
                    <button type="submit" class="btn-primary">Crear Tabla</button>
                </form>
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const createTableModal = new bootstrap.Modal(document.getElementById('createTableModal'));
        createTableModal.show();
    });
</script>
@endif

@if (!Schema::hasTable('pacientes'))
    <div class="alert alert-danger">
        El módulo de pacientes está restringido porque no existe la tabla <strong>pacientes</strong>.
    </div>
@endif

@if ($showCreateTableModal)
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const createTableModal = new bootstrap.Modal(document.getElementById('createTableModal'));
        createTableModal.show();
    });
</script>
@endif

<!-- Modal de Registro -->
<div class="modal fade" id="registerPatientModal" tabindex="-1" aria-labelledby="registerPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerPatientModalLabel">Registrar Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="registerPatientForm" method="POST" action="{{ route('pacientes.store') }}">
                    @csrf
                    <!-- RUT -->
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT</label>
                        <input type="text" class="form-control" id="rut" name="rut" required>
                    </div>
                    <!-- Nombres -->
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>
                    <!-- Apellidos -->
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="+569XXXXXXXX" required>
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <!-- Género -->
                    <div class="mb-3">
                        <label for="genero" class="form-label">Género</label>
                        <select class="form-select" id="genero" name="genero" required>
                            <option value="">Selecciona</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <!-- Fecha de Nacimiento -->
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                    </div>
                    <button type="submit" class="btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@endsection


