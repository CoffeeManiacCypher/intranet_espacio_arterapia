document.addEventListener('DOMContentLoaded', () => {
    const sidebarLinks = document.querySelectorAll('.sidebar-content'); // Botones del sidebar
    const modal = document.getElementById('unsaved-changes-modal'); // Modal de advertencia
    const continueButton = document.getElementById('continue-editing'); // Botón "Continuar"
    const discardButton = document.getElementById('discard-changes'); // Botón "Descartar"
    const formInputs = document.querySelectorAll('#form-paciente input, #form-paciente select');
    const ciudadSelect = document.getElementById('ciudad_id');
    const comunaSelect = document.getElementById('comuna_id');
    const form = document.getElementById('form-paciente'); // Formulario
    const emailInput = document.getElementById('email'); // Campo de email
    const rutInput = document.getElementById('rut'); // Campo de RUT
    const errorContainer = document.createElement('div'); // Contenedor para errores
    errorContainer.classList.add('error-container'); // Clase para el contenedor de errores
    form.prepend(errorContainer); // Agregarlo al inicio del formulario

    const baseUrl = '/pacientes/validar'; // Ruta para validación AJAX

    let unsavedChanges = false; // Estado para detectar cambios
    let targetHref = null; // Guardar el destino de la navegación

    // Mostrar mensajes de error
    const mostrarError = (mensaje) => {
        errorContainer.innerHTML = `<p class="error-message">${mensaje}</p>`;
        errorContainer.style.display = 'block';
    };

    // Limpiar errores
    const limpiarErrores = () => {
        errorContainer.innerHTML = '';
        errorContainer.style.display = 'none';
    };

    // Detectar cambios en los inputs
    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            unsavedChanges = true;
        });
    });

    // Detectar clics en los enlaces del sidebar
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault(); // Bloquear navegación inmediata
            const url = link.getAttribute('data-url'); // Extraer la URL desde el atributo data-url

            if (unsavedChanges) {
                targetHref = url; // Guardar la URL de destino
                modal.style.visibility = 'visible';
                modal.classList.add('show');
            } else {
                window.location.href = url; // Redirigir si no hay cambios no guardados
            }
        });
    });

    // Configurar el botón "Continuar"
    continueButton.addEventListener('click', () => {
        modal.classList.remove('show');
        modal.style.visibility = 'hidden';
    });

    // Configurar el botón "Descartar"
    discardButton.addEventListener('click', () => {
        unsavedChanges = false; // Restablecer el estado
        modal.classList.remove('show');
        modal.style.visibility = 'hidden';
        if (targetHref) {
            window.location.href = targetHref; // Redirigir al nuevo enlace
        }
    });

    // Filtrar comunas según la ciudad seleccionada
    ciudadSelect.addEventListener('change', () => {
        const ciudadId = ciudadSelect.value;

        Array.from(comunaSelect.options).forEach(option => {
            if (option.value === "" || option.getAttribute('data-ciudad-id') === ciudadId) {
                option.style.display = 'block'; // Mostrar opciones válidas
            } else {
                option.style.display = 'none'; // Ocultar opciones no válidas
            }
        });

        // Restablecer la selección de comuna
        comunaSelect.value = "";
    });

    // Validación del email y RUT antes de enviar
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // Prevenir el envío del formulario
        limpiarErrores(); // Limpiar errores previos

        const email = emailInput.value.trim();
        const rut = rutInput.value.trim();

        try {
            const response = await fetch(baseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ email, rut }),
            });

            const data = await response.json();

            if (!response.ok || !data.valid) {
                mostrarError(data.message || 'Error desconocido al validar los datos.');
            } else {
                form.submit(); // Si no hay errores, enviar el formulario
            }
        } catch (error) {
            console.error('Error en la validación:', error);
            mostrarError('Ocurrió un error al validar los datos. Inténtalo nuevamente.');
        }
    });

    // Restablecer comunas al cargar la página
    const resetComunas = () => {
        const ciudadId = ciudadSelect.value;

        Array.from(comunaSelect.options).forEach(option => {
            if (option.value === "" || option.getAttribute('data-ciudad-id') === ciudadId) {
                option.style.display = 'block'; // Mostrar opciones válidas
            } else {
                option.style.display = 'none'; // Ocultar opciones no válidas
            }
        });

        // Asegurarse de que la comuna seleccionada sea válida
        if (!comunaSelect.querySelector(`option[value="${comunaSelect.value}"][style="display: block;"]`)) {
            comunaSelect.value = "";
        }
    };

    // Llamar al reset de comunas al cargar la página
    resetComunas();
});
