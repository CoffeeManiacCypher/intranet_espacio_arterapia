@extends('layouts.module')

@vite(['resources/css/pacientes.css'])
@vite(['resources/css/filtro.css'])
@vite(['resources/js/pacientes.js'])


@section('title', 'Módulo de Pacientes')

@section('sidebar')
    <button class="sidebar-content" onclick="window.location='{{ route('pacientes.index') }}'">
        Registro de Pacientes
    </button>
    <button class="sidebar-content" onclick="window.location='{{ route('pacientes.create') }}'">
        Añadir Paciente
    </button>
    <button class="sidebar-content">Ver Fichas Médicas</button>
    <button class="sidebar-content">Analíticas</button>
@endsection

@section('content')
<div class="pacientes-container">

    <!-- Acciones generales -->
    <div class="pacientes-acciones">
        <button class="btn btn-primary" data-modal-target=".modal-import">
            <i class="bi bi-file-earmark-arrow-down"></i> Importar Pacientes
        </button>
        <button class="btn btn-secondary" data-modal-target=".modal-export">
            <i class="bi bi-file-earmark-arrow-up"></i> Exportar Pacientes
        </button>
    </div>

    <!-- Tabla de Pacientes -->
    <div class="pacientes-tabla">
        <div class="tabla-contenedor">
            <div class="tabla-scroll">
                <table class="tabla-dinamica">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" class="checkbox" id="select-all" />
                                    <span class="custom-checkbox"></span>
                                </label>
                            </th>
                            <th class="sortable" data-column="0">ID <i class="sort-icon bi bi-chevron-expand"></i></th>
                            <th class="sortable" data-column="1">Nombres <i class="sort-icon bi bi-chevron-expand"></i></th>
                            <th class="sortable" data-column="2">Apellidos <i class="sort-icon bi bi-chevron-expand"></i></th>
                            <th class="sortable" data-column="3">Teléfono <i class="sort-icon bi bi-chevron-expand"></i></th>
                            <th class="sortable" data-column="4">Estado <i class="sort-icon bi bi-chevron-expand"></i></th>
                            <th class="sortable" data-column="5">Fecha Registro <i class="sort-icon bi bi-chevron-expand"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                        <tr data-paciente-id="{{ $paciente->id }}" data-has-modal="true">
                            <td>
                                <label class="checkbox-wrapper">
                                    <input type="checkbox" class="checkbox" />
                                    <span class="custom-checkbox"></span>
                                </label>
                            </td>
                            <td>{{ $paciente->id }}</td>
                            <td>{{ $paciente->nombres ?? 'N/A' }}</td>
                            <td>{{ $paciente->apellidos ?? 'N/A' }}</td>
                            <td>{{ $paciente->telefono ?? 'Sin Teléfono' }}</td>
                            <td>
                                <span class="badge {{ $paciente->verificado === 'verificado' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($paciente->verificado) }}
                                </span>
                            </td>
                            <td>{{ $paciente->fecha_creacion }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modales -->
    <!-- Modal Importar -->
    <!-- Modal Importar -->
    <div class="modal modal-import">
        <div class="modal-content">
            <button class="btn-close" onclick="closeModal('.modal-import')">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <h2>Importar Pacientes</h2>
            <div class="modal-body">
                <form action="{{ route('pacientes.importar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="archivo-import">Seleccione el archivo para importar (.xlsx):</label>
                    <input type="file" name="archivo" id="archivo-import" accept=".xlsx" required>
                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">Importar</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('.modal-import')">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Exportar -->
    <div class="modal modal-export">
        <div class="modal-content">
            <button class="btn-close" onclick="closeModal('.modal-export')">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <h2>Exportar Pacientes</h2>
            <div class="modal-body">
                <p>Seleccione el formato para exportar:</p>
                <a href="{{ route('pacientes.exportar', ['formato' => 'csv']) }}" class="btn btn-primary">Exportar CSV</a>
                <a href="{{ route('pacientes.exportar', ['formato' => 'xlsx']) }}" class="btn btn-primary">Exportar XLSX</a>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('.modal-export')">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-detalle-paciente" class="modal">
        <div class="modal-content">
            <button class="btn-close-modal">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <h2>Detalles del Paciente</h2>
            <div class="modal-body">
                <p><strong>ID:</strong> <span id="detalle-id"></span></p>
                <p><strong>Nombre:</strong> <span id="detalle-nombre"></span></p>
                <p><strong>Apellido:</strong> <span id="detalle-apellido"></span></p>
                <p><strong>Email:</strong> <span id="detalle-email"></span></p>
                <p><strong>Teléfono:</strong> <span id="detalle-telefono"></span></p>
                <p><strong>Teléfono Secundario:</strong> <span id="detalle-telefono-secundario"></span></p>
                <p><strong>Dirección:</strong> <span id="detalle-direccion"></span></p>
                <p><strong>Comuna:</strong> <span id="detalle-comuna"></span></p>
                <p><strong>Ciudad:</strong> <span id="detalle-ciudad"></span></p>
                <p><strong>Fecha de Nacimiento:</strong> <span id="detalle-fecha-nacimiento"></span></p>
                <p><strong>Edad:</strong> <span id="detalle-edad"></span></p>
                <p><strong>Género:</strong> <span id="detalle-genero"></span></p>
                <p><strong>Estado:</strong> <span id="detalle-estado"></span></p>
                <p><strong>Fecha de Registro:</strong> <span id="detalle-fecha-registro"></span></p>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary btn-close-modal">Cerrar</button>
            </div>
        </div>
    </div>











    <div id="pacientes-data" style="display: none;">
        {{ json_encode($pacientes) }}
    </div>

</div>
@endsection
