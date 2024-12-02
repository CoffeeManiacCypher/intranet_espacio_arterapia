@extends('layouts.module')

@vite(['resources/css/pacientes.css'])
@vite(['resources/css/filtro.css'])
@vite(['resources/js/tabla.js'])
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
     <!-- Controles de Paginación -->
     <div class="tabla-controles">

        <label for="rows-per-page">Mostrar:</label>

        <select id="rows-per-page">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
        </select>

        <div class="tabla-navegacion">
            @if ($pacientes->onFirstPage())
                <button disabled>Anterior</button>
            @else
                <a href="{{ $pacientes->previousPageUrl() }}&per_page={{ request('per_page', 10) }}" button class="btn btn-primary">Anterior</a>
            @endif

            <span>Página {{ $pacientes->currentPage() }} de {{ $pacientes->lastPage() }}</span>

            @if ($pacientes->hasMorePages())
                <a href="{{ $pacientes->nextPageUrl() }}&per_page={{ request('per_page', 10) }}" button class="btn btn-primary">Siguiente</a>
            @else
                <button disabled>Siguiente</button>
            @endif
        </div>
    </div>

    <!-- Tabla -->
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

    <div class="pacientes-filtro">
    <form method="GET" action="{{ route('pacientes.index') }}">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}">
        </div>

        <div>
            <label for="rut">RUT:</label>
            <input type="text" name="rut" id="rut" value="{{ request('rut') }}">
        </div>

        <div>
            <label for="correo">Correo Electrónico:</label>
            <input type="email" name="correo" id="correo" value="{{ request('correo') }}">
        </div>

        <div>
            <label for="verificado">Estado de Verificación:</label>
            <select name="verificado" id="verificado">
                <option value="">Todos</option>
                <option value="verificado" {{ request('verificado') == 'verificado' ? 'selected' : '' }}>Verificados</option>
                <option value="pendiente" {{ request('verificado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
            </select>
        </div>

        <div>
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" value="{{ request('telefono') }}">
        </div>

        <div>
            <label for="fecha_registro">Fecha de Registro:</label>
            <input type="date" name="fecha_registro" value="{{ request('fecha_registro') }}">
        </div>

        <!-- Filtro de Ciudad -->
        <div>
            <label for="ciudad">Ciudad:</label>
            <select name="ciudad" id="ciudad">
                <option value="">Todas</option>
                @foreach ($ciudades as $ciudad)
                    <option value="{{ $ciudad->id }}" {{ request('ciudad') == $ciudad->id ? 'selected' : '' }}>{{ $ciudad->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filtro de Comuna -->
        <div>
            <label for="comuna">Comuna:</label>
            <select name="comuna" id="comuna">
                <option value="">Todas</option>
                @foreach ($comunas as $comuna)
                    <option value="{{ $comuna->id }}" data-ciudad-id="{{ $comuna->ciudad_id }}" {{ request('comuna') == $comuna->id ? 'selected' : '' }}>
                        {{ $comuna->nombre }}
                    </option>
                @endforeach
            </select>
        </div>



        <button type="submit">Filtrar</button>
    </form>


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
            <button class="btn-close" onclick="closeModal('#modal-detalle-paciente')">
                <i class="bi bi-x-circle-fill"></i>
            </button>

            <h2>Detalles del Paciente</h2>
            <div class="modal-body">
                <p><strong>Estado:</strong> <span id="detalle-estado"></span></p>
                <p><strong>ID:</strong> <span id="detalle-id"></span></p>
                <p><strong>Nombre:</strong> <span id="detalle-nombre"></span></p>
                <p><strong>Apellido:</strong> <span id="detalle-apellido"></span></p>
                <p><strong>RUT:</strong> <span id="detalle-rut"></span></p>
                <p><strong>Email:</strong> <span id="detalle-email"></span></p>
                <p><strong>Teléfono:</strong> <span id="detalle-telefono"></span></p>
                <p><strong>Dirección:</strong> <span id="detalle-direccion"></span></p>
                <p><strong>Comuna:</strong> <span id="detalle-comuna"></span></p>
                <p><strong>Ciudad:</strong> <span id="detalle-ciudad"></span></p>
                <p><strong>Fecha de Nacimiento:</strong> <span id="detalle-fecha-nacimiento"></span></p>
                <p><strong>Edad:</strong> <span id="detalle-edad"></span></p>
                <p><strong>Género:</strong> <span id="detalle-genero"></span></p>

                <p><strong>Fecha de Registro:</strong> <span id="detalle-fecha-registro"></span></p>
                <p><strong>Comentario adicional:</strong> <span id="detalle-telefono-secundario"></span></p>
            </div>
                <div class="modal-actions">
                    <button class="btn btn-primary" id="btn-editar-paciente">Editar</button>
                    <button class="btn btn-danger" onclick="abrirModalEliminar()">Eliminar</button> <!-- Nuevo botón de eliminar -->
                </div>
        </div>
    </div>
 
    <!-- Modal de Edición del Paciente -->
    <div id="modal-editar-paciente" class="modal">
        <div class="modal-content">
            <button class="btn-close" onclick="cerrarModal('#modal-editar-paciente')">
                <i class="bi bi-x-circle-fill"></i>
            </button>

            <h2>Editar Paciente</h2>
            <form id="form-editar-paciente">
                <div class="modal-body">
                    <!-- Campos del formulario -->
                    <label for="editar-rut">RUT:</label>
                    <input type="text" id="editar-rut" name="rut" required>

                    <label for="editar-nombre">Nombre:</label>
                    <input type="text" id="editar-nombre" name="nombres" required>

                    <label for="editar-apellido">Apellido:</label>
                    <input type="text" id="editar-apellido" name="apellidos" required>

                    <label for="editar-email">Email:</label>
                    <input type="email" id="editar-email" name="email" required>

                    <label for="editar-telefono">Teléfono:</label>
                    <input type="text" id="editar-telefono" name="telefono">

                    <label for="editar-direccion">Dirección:</label>
                    <input type="text" id="editar-direccion" name="direccion">

                    <label for="editar-comuna">Comuna:</label>
                    <input type="text" id="editar-comuna" name="comuna">

                    <label for="editar-ciudad">Ciudad:</label>
                    <input type="text" id="editar-ciudad" name="ciudad">

                    <label for="editar-fecha-nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="editar-fecha-nacimiento" name="fecha_nacimiento">

                    <label for="editar-genero">Género:</label>
                    <select id="editar-genero" name="genero" required>
                        <option value="Femenino">Femenino</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal('#modal-editar-paciente')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-eliminar-paciente" class="modal">
        <div class="modal-content">
            <button class="btn-close" onclick="cerrarModal('#modal-eliminar-paciente')">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <h2>Confirmar Eliminación</h2>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este paciente? Esta acción no se puede deshacer y todos los datos vinculados al paciente también serán eliminados.</p>
                <div class="modal-actions">
                    <button id="confirmar-eliminar" class="btn btn-danger">Eliminar</button>
                    <button class="btn btn-secondary" onclick="cerrarModal('#modal-eliminar-paciente')">Cancelar</button>
                </div>
            </div>
        </div>
    </div>








    <div id="pacientes-data" style="display: none;">
        {!! json_encode($pacientes->items()) !!}
    </div>



</div>
@endsection
