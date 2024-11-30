@extends('layouts.module')

@vite(['resources/css/pacientes.css'])
@vite(['resources/css/filtro.css'])

@section('title', 'Módulo de Pacientes')

<script>
    // Función para abrir el modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
        }
    }

    // Función para cerrar el modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Abrir modal al hacer clic en los botones
        document.querySelectorAll('[data-modal-target]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-target');
                openModal(modalId);
            });
        });

        // Cerrar modal al hacer clic fuera del contenido
        window.addEventListener('click', (event) => {
            document.querySelectorAll('.modal').forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    });
</script>

@section('sidebar')
    <button class="sidebar-content" onclick="window.location='/pacientes'">Registro de Pacientes</button>
    <button class="sidebar-content" onclick="window.location='/pacientes/create'">Añadir Paciente</button>
    <button class="sidebar-content" onclick="window.location='/fichas'">Ver Fichas Médicas</button>
    <button class="sidebar-content" onclick="window.location='/analiticas'">Analíticas</button>
@endsection

@section('content-title', 'Registro de Pacientes')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Comuna</th>
                <th>Ciudad</th>
                <th>Fecha de Nacimiento</th>
                <th>Edad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pacientes as $paciente)
            <tr>
                <td>{{ $paciente->id }}</td>
                <td>{{ $paciente->nombres }} {{ $paciente->apellidos }}</td>
                <td>{{ $paciente->email }}</td>
                <td>{{ $paciente->telefono }}</td>
                <td>{{ $paciente->comuna->nombre ?? 'N/A' }}</td>
                <td>{{ $paciente->ciudad->nombre ?? 'N/A' }}</td>
                <td>{{ $paciente->fecha_nacimiento }}</td>
                <td>{{ $paciente->edad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <div class="form-group">
        <label for="ciudad">Ciudad</label>
        <select id="ciudad" class="form-control">
            <option value="">Seleccione una ciudad</option>
            @foreach($ciudades as $ciudad)
                <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="comuna">Comuna</label>
        <select id="comuna" class="form-control">
            <option value="">Seleccione una comuna</option>
        </select>
    </div>

@endsection
