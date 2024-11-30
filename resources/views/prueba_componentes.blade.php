<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Componentes</title>

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    
</head>


<body>

    <h1>Prueba de Componentes</h1>

    <section>
        <h2>Botones</h2>
        <button class="btn">Botón General</button>
        <button class="btn btn-primary">Botón Primario</button>
        <button class="btn btn-secondary">Botón Secundario</button>
        <button class="btn btn-success">Botón Success</button>
        <button class="btn btn-danger">Botón Peligro</button>
    </section>

    </br>

    <section>

    <div class="input-container">

        <div class="input-group">
            <!-- Icono  -->
            <span class="input-group-text">
             <i class="bi bi-person-vcard-fill"></i>
            </span>

            <input type="text" class="input" id="input-text" placeholder=" ">
            
            <label for="input-text" class="label">Nombre</label>
        </div>

    </div>

    <form class="form-container">
        <div class="input-container" id="email-container">
            <div class="input-group">
                <span class="input-group-text">
                    <!-- Ícono de correo -->
                    <i class="bi bi-envelope-fill"></i>
                </span>
                <input type="email" class="input email" id="email" name="email" placeholder="" required>
                <label for="email" class="label">Correo electrónico</label>
                
            </div>
            <!-- Mensaje de error oculto por defecto -->
            <small class="error-message" id="email-error-message">Ingresa un formato de correo adecuado, como ejemplo@email.com</small>
        </div>

        </br>

        <div class="input-container" id="email-container">
            <div class="input-group">
                <span class="input-group-text">
                    <!-- Ícono de correo -->
                    <i class="bi bi-envelope-fill"></i>
                </span>
                <input type="email" class="input email" id="email" name="email" placeholder="" >
                <label for="email" class="label">Correo electrónico</label>
            </div>
            <!-- Mensaje de error oculto por defecto -->
            <small class="error-message" id="email-error-message">Ingresa un formato de correo adecuado, como ejemplo@email.com</small>
        </div>


        <button type="submit" class="btn btn-secondary">Botón Secundario</button>

    </form>


    </section>
    <form class="form-container">
        <div class="checkbox-container">
            <label class="checkbox-wrapper">
                <input type="checkbox" class="checkbox checkbox-success" />
                <span class="custom-checkbox"></span>
                Activar Opción Verde
            </label>

            <label class="checkbox-wrapper">
                <input type="checkbox" class="checkbox checkbox-danger" />
                <span class="custom-checkbox"></span>
                Activar Opción Roja
            </label>

            <label class="checkbox-wrapper">
                <input type="checkbox" class="checkbox" disabled />
                <span class="custom-checkbox"></span>
                Opción Deshabilitada
            </label>

            <label class="checkbox-wrapper">
                <input type="checkbox" class="checkbox" />
                <span class="custom-checkbox"></span>
                Activar Opción
            </label>
        </div>

        <div class="radio-container">
            <h2>Selecciona una opción</h2>
            <div class="radio-group">
                <label class="radio-wrapper">
                    <input type="radio" name="group1" value="opcion1" class="radio">
                    <span class="custom-radio"></span>
                    Opción 1
                </label>
                <label class="radio-wrapper">
                    <input type="radio" name="group1" value="opcion2" class="radio">
                    <span class="custom-radio"></span>
                    Opción 2
                </label>
                <label class="radio-wrapper">
                    <input type="radio" name="group1" value="otro" class="radio" id="radio-otro">
                    <span class="custom-radio"></span>
                    Otro
                </label>
                <div id="custom-input-container" class="hidden">
                    <input type="text" placeholder="Especifica aquí" id="custom-input">
                </div>
            </div>

            <!-- Mover aquí el botón -->
            <button id="reset-button" class="btn-reset hidden">Borrar Selección</button>
            <small class="error-message hidden" id="error-message">Por favor, selecciona una opción</small>

        </div>
    </form>

    <button id="btn-open-modal" class="btn btn-primary">Abrir Modal</button>

    <div id="modal" class="modal">
        <div class="modal-content">
            <button id="btn-close" class="btn-close">
                <i class="bi bi-x-circle-fill"></i>
            </button>
            <h2 class="modal-title">Título del Modal</h2>
            <div class="modal-body">
                Este es el contenido del modal.
            </div>
            <div class="modal-actions">
                <!-- Botón dentro del modal -->
                <button
                    class="btn btn-primary"
                    data-alert
                    data-alert-type="success"
                    data-alert-message="¡Operación exitosa desde el modal!"
                    data-alert-duration="4000"
                >
                    Confirmar
                </button>

            </div>
        </div>
    </div>

    <div id="alert-container"></div>

<!-- Botones para probar -->
<button
    class="btn btn-danger"
    data-alert
    data-alert-type="error"
    data-alert-message="Ocurrió un error inesperado."
    data-alert-duration="5000"
>
    Mostrar Error
</button>

<button
    class="btn btn-primary"
    data-alert
    data-alert-type="success"
    data-alert-message="¡Operación completada con éxito!"
    data-alert-duration="5000"
>
    Mostrar Éxito
</button>

<button
    class="btn btn-secondary"
    data-alert
    data-alert-type="warning"
    data-alert-message="¡verifica los datos!"
    data-alert-duration="5000"
>
    Mostrar Éxito
</button>

</body>

</html>
