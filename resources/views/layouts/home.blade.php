<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Menú Principal')</title>

    <!-- Cargar estilos -->
    @php
        $cssFiles = array_map(function($file) {
            return asset('css/' . basename($file));
        }, glob(public_path('css/*.css')));
    @endphp

    @foreach ($cssFiles as $cssFile)
        <link rel="stylesheet" href="{{ $cssFile }}">
    @endforeach

    @php
        $jsFiles = array_map(function($file) {
            return asset('js/' . basename($file));
        }, glob(public_path('js/*.js')));
    @endphp

    @foreach ($jsFiles as $jsFile)
        <script src="{{ $jsFile }}" defer></script>
    @endforeach

    @vite(['resources/css/home.css']) <!-- CSS específico de HOME -->
    @vite(['resources/js/home.js']) <!-- CSS específico de HOME -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    
    <div class="home-layout">
        <div class="animated-background"> </div>

            <main>
                @yield('content') <!-- Sección de contenido dinámico -->
            </main>
       
        <!-- Contenedor principal -->

    </div>

    <!-- Scripts generales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
