<div class="filters-container">
    <form action="{{ $action }}" method="GET" class="filters-form">
        @csrf
        <!-- Título del filtro -->
        <h3 class="filters-title">{{ $title ?? 'Filtros' }}</h3>
        
        <!-- Espacio para los filtros personalizados -->
        <div class="filters-content">
            {{ $slot }}
        </div>

        <!-- Botón para aplicar filtros -->
        <div class="filters-actions">
            <button type="submit" class="btn btn-primary">Aplicar filtros</button>
            @isset($resetRoute)
                <a href="{{ $resetRoute }}" class="btn btn-secondary">Limpiar filtros</a>
            @endisset
        </div>
    </form>
</div>
