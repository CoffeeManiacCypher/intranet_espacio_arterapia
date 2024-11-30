@extends('layouts.module')
@vite(['resources/css/pacientes.css'])
@vite(['resources/css/filtro.css'])


@section('title', 'Añadir Paciente')

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
<div class="pacientes-create-form">
    <form method="POST" action="{{ route('pacientes.store') }}">
        @csrf

        <!-- RUT -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="rut" name="rut" class="input" placeholder=" " 
                       pattern="\d{1,2}\.\d{3}\.\d{3}-[0-9Kk]" title="Ingrese un RUT válido" required>
                <label for="rut" class="label">RUT</label>
            </div>
        </div>

        <!-- Nombres -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="nombres" name="nombres" class="input" placeholder=" " required>
                <label for="nombres" class="label">Nombres</label>
            </div>
        </div>

        <!-- Apellidos -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="apellidos" name="apellidos" class="input" placeholder=" " required>
                <label for="apellidos" class="label">Apellidos</label>
            </div>
        </div>

        <!-- Email -->
        <div class="input-container">
            <div class="input-group">
                <input type="email" id="email" name="email" class="input" placeholder=" " required>
                <label for="email" class="label">Email</label>
            </div>
        </div>

        <!-- Teléfono -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="telefono" name="telefono" class="input" placeholder=" " 
                       pattern="\+?56[0-9]{9}" title="Ingrese un teléfono válido (ejemplo: +56912345678)" required>
                <label for="telefono" class="label">Teléfono</label>
            </div>
        </div>

        <!-- Teléfono Secundario -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="telefono_secundario" name="telefono_secundario" class="input" placeholder=" ">
                <label for="telefono_secundario" class="label">Teléfono Secundario</label>
            </div>
        </div>

        <!-- Dirección -->
        <div class="input-container">
            <div class="input-group">
                <input type="text" id="direccion" name="direccion" class="input" placeholder=" ">
                <label for="direccion" class="label">Dirección</label>
            </div>
        </div>

        <!-- Ciudad -->
        <div class="input-container">
            <div class="input-group">
                <select id="ciudad_id" name="ciudad_id" class="input" required>
                    <option value="">Seleccione una ciudad</option>
                    @foreach ($ciudades as $ciudad)
                        <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                    @endforeach
                </select>
                <label for="ciudad_id" class="label">Ciudad</label>
            </div>
        </div>

        <!-- Comuna -->
        <div class="input-container">
            <div class="input-group">
                <select id="comuna_id" name="comuna_id" class="input" required>
                    <option value="">Seleccione una comuna</option>
                    @foreach ($comunas as $comuna)
                        <option value="{{ $comuna->id }}" data-ciudad-id="{{ $comuna->ciudad_id }}">{{ $comuna->nombre }}</option>
                    @endforeach
                </select>
                <label for="comuna_id" class="label">Comuna</label>
            </div>
        </div>

        <!-- Fecha de Nacimiento -->
        <div class="input-container">
            <div class="input-group">
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="input" required>
                <label for="fecha_nacimiento" class="label">Fecha de Nacimiento</label>
            </div>
        </div>

        <!-- Género -->
        <div class="input-container">
            <div class="input-group">
                <select id="genero" name="genero" class="input" required>
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Otro">Otro</option>
                </select>
                <label for="genero" class="label">Género</label>
            </div>
        </div>

        <!-- Botón de Guardar -->
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

@endsection
