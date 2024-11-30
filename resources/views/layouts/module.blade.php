<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Módulo')</title>

    <!-- Para que cargue todos los componentes de css -->
    @php
        $cssFiles = glob(public_path('css/*.css')); 
    @endphp

    @foreach ($cssFiles as $cssFile)
        <link rel="stylesheet" href="{{ asset('css/' . basename($cssFile)) }}">
    @endforeach

     <!-- Para que cargue todos los componentes de js -->

    @php
        $jsFiles = glob(public_path('js/*.js')); 
    @endphp

    @foreach ($jsFiles as $jsFile)
        <script src="{{ asset('js/' . basename($jsFile)) }}"></script>
    @endforeach

    <!-- css adicionales -->
    @vite(['resources/css/module.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    
</head>

<body>

    <div class="layout-container">

        <!-- Es el menú de opciones donde iran todas las acciones o areas del modulo en si -->
        <aside class="layout-sidebar">
            @yield('sidebar')
        </aside>

        <!-- Esta es el area del contenido principal, donde iran los demas containers para mostrar mas info de la seccion, como el típico CRUD-->
        <main class="layout-content">
            <div class="content-wrapper">
                <div class="content-main">
                    @yield('content')
                </div>
            </div>
        </main>
        
    </div>

</body>
</html>