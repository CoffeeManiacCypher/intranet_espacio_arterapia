document.addEventListener('DOMContentLoaded', () => { 
    const tablaBody = document.querySelector('.tabla-dinamica tbody');
    const btnCloseModal = document.querySelector('.btn-close-modal');
    const rowsPerPageSelect = document.getElementById('rows-per-page');
    const paginationControls = document.querySelector('.tabla-navegacion');
    const modalDetallePaciente = document.getElementById('modal-detalle-paciente');
    const modalEditarPaciente = document.getElementById('modal-editar-paciente');
    const btnEditarPaciente = document.getElementById('btn-editar-paciente');
    const filtrosForm = document.querySelector('.pacientes-filtro form');
    const ciudadSelect = document.getElementById('ciudad');
    const comunaSelect = document.getElementById('comuna');
    let pacientesData = []; // Array dinámico de pacientes
    let currentPage = 1; // Página actual
    let pacienteSeleccionado = null; // Paciente seleccionado para edición

    // Filtrar comunas según la ciudad seleccionada
    ciudadSelect.addEventListener('change', () => {
        const ciudadId = ciudadSelect.value;
        
        // Ocultar comunas que no pertenezcan a la ciudad seleccionada
        Array.from(comunaSelect.options).forEach(option => {
            if (option.value === "" || option.dataset.ciudadId === ciudadId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });

        // Reiniciar el valor del campo comuna
        comunaSelect.value = "";
    });

    // Función para obtener los valores de los filtros actuales en el formulario
    function obtenerFiltros() {
        const formData = new FormData(filtrosForm);
        const params = new URLSearchParams();
        formData.forEach((value, key) => {
            if (value) {
                params.append(key, value);
            }
        });
        return params.toString();
    }

    ciudadSelect.addEventListener('change', () => {
        actualizarTabla(rowsPerPageSelect.value, 1);
    });
    
    comunaSelect.addEventListener('change', () => {
        actualizarTabla(rowsPerPageSelect.value, 1);
    });
    

    // Función para actualizar la tabla con datos desde el servidor
    function actualizarTabla(perPage = 10, pagina = 1) {
        const filtros = obtenerFiltros();
        const url = `/pacientes?page=${pagina}&per_page=${perPage}&${filtros}`;
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((response) => response.json())
            .then((data) => {
                pacientesData = data.data; // Actualizar datos de pacientes
                currentPage = data.current_page; // Actualizar página actual

                // Limpiar el cuerpo de la tabla
                tablaBody.innerHTML = '';

                // Agregar nuevas filas
                pacientesData.forEach((paciente) => {
                    const row = document.createElement('tr');
                    row.setAttribute('data-paciente-id', paciente.id);
                    row.setAttribute('data-has-modal', 'true');
                    row.innerHTML = `
                        <td>
                            <label class="checkbox-wrapper">
                                <input type="checkbox" class="checkbox" />
                                <span class="custom-checkbox"></span>
                            </label>
                        </td>
                        <td>${paciente.id}</td>
                        <td>${paciente.nombres || 'N/A'}</td>
                        <td>${paciente.apellidos || 'N/A'}</td>
                        <td>${paciente.telefono || 'Sin Teléfono'}</td>
                        <td>
                            <span class="badge ${paciente.verificado === 'verificado' ? 'badge-success' : 'badge-warning'}">
                                ${paciente.verificado.charAt(0).toUpperCase() + paciente.verificado.slice(1)}
                            </span>
                        </td>
                        <td>${paciente.fecha_creacion}</td>
                    `;
                    tablaBody.appendChild(row);
                });

                // Actualizar controles de navegación
                actualizarControlesPaginacion(data);
            })
            .catch((error) => console.error('Error al actualizar la tabla:', error));
    }

    // Función para actualizar controles de paginación
    function actualizarControlesPaginacion(data) {
        const filtros = obtenerFiltros();
        paginationControls.innerHTML = `
            ${data.prev_page_url
                ? `<button class="btn btn-primary" data-page="${data.current_page - 1}" data-filtros="${filtros}">Anterior</button>`
                : `<button class="btn" disabled>Anterior</button>`}
            <span>Página ${data.current_page} de ${data.last_page}</span>
            ${data.next_page_url
                ? `<button class="btn btn-primary" data-page="${data.current_page + 1}" data-filtros="${filtros}">Siguiente</button>`
                : `<button class="btn" disabled>Siguiente</button>`}
        `;

        // Añadir eventos a los botones de paginación
        paginationControls.querySelectorAll('button[data-page]').forEach((button) => {
            button.addEventListener('click', () => {
                const page = button.getAttribute('data-page');
                actualizarTabla(rowsPerPageSelect.value, page);
            });
        });
    }

    // Escuchar el cambio en filas por página
    rowsPerPageSelect.addEventListener('change', () => {
        const perPage = rowsPerPageSelect.value;
        actualizarTabla(perPage, 1); // Reiniciar a la primera página
    });

    // Evento para aplicar los filtros
    filtrosForm.addEventListener('submit', (e) => {
        e.preventDefault();
        actualizarTabla(rowsPerPageSelect.value, 1); // Reiniciar a la primera página al filtrar
    });

    // Delegar eventos para abrir el modal desde las filas de la tabla
    if (tablaBody) {
        tablaBody.addEventListener('click', (e) => {
            const row = e.target.closest('tr[data-has-modal="true"]');
            if (row) {
                const pacienteId = row.getAttribute('data-paciente-id');
                actualizarEdad(pacienteId).then(() => {
                    abrirModalPaciente(pacienteId);
                });
            }
        });
    }

    // Función para actualizar la edad en el servidor
    async function actualizarEdad(pacienteId) {
        try {
            const response = await fetch(`/pacientes/${pacienteId}/actualizar-edad`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            if (!response.ok) {
                throw new Error(`No se pudo actualizar la edad del paciente con ID ${pacienteId}.`);
            }
        } catch (error) {
            console.error(error.message);
        }
    }

    // Función para abrir el modal del paciente
    function abrirModalPaciente(pacienteId) {
        pacienteSeleccionado = pacientesData.find((p) => p.id == pacienteId);
        if (pacienteSeleccionado) {
            rellenarModal(pacienteSeleccionado);
            mostrarModal('modal-detalle-paciente');
        } else {
            console.error('Paciente no encontrado:', pacienteId);
        }
    }

    // Función para rellenar los datos en el modal de detalles del paciente
    function rellenarModal(paciente) {
        const mostrarValorConAdvertencia = (valor, mensajeVacio) => {
            if (!valor) {
                return `<span class="campo-vacio-importante">${mensajeVacio} <i class="bi bi-exclamation-circle-fill text-danger" title="¡Este dato es importante!"></i></span>`;
            }
            return `<span class="campo-completo">${valor}</span>`;
        };

        const mostrarValor = (valor, mensajeVacio) => {
            if (!valor || valor === '0000-00-00') {
                return `<span class="campo-vacio">${mensajeVacio}</span>`;
            }
            return `<span class="campo-completo">${valor}</span>`;
        };

        // Rellenar los campos con el valor correspondiente o mostrar advertencia si están vacíos
        document.getElementById('detalle-id').innerHTML = mostrarValor(paciente?.id, 'ID no disponible');
        document.getElementById('detalle-rut').innerHTML = mostrarValorConAdvertencia(paciente?.rut, 'RUT no registrado');
        document.getElementById('detalle-nombre').innerHTML = mostrarValorConAdvertencia(paciente?.nombres, 'Nombre no registrado');
        document.getElementById('detalle-apellido').innerHTML = mostrarValorConAdvertencia(paciente?.apellidos, 'Apellido no registrado');
        document.getElementById('detalle-email').innerHTML = mostrarValor(paciente?.email, 'Ninguno');
        document.getElementById('detalle-telefono').innerHTML = mostrarValor(paciente?.telefono, 'Ninguno');
        document.getElementById('detalle-direccion').innerHTML = mostrarValor(paciente?.direccion, 'No registrada');
        document.getElementById('detalle-comuna').innerHTML = mostrarValor(paciente?.comuna?.nombre, 'No especificado');
        document.getElementById('detalle-ciudad').innerHTML = mostrarValor(paciente?.ciudad?.nombre, 'No especificado');
        document.getElementById('detalle-fecha-nacimiento').innerHTML = mostrarValor(paciente?.fecha_nacimiento, 'No especificado');
        document.getElementById('detalle-edad').innerHTML = paciente?.edad >= 0 
            ? `<span class="campo-completo">${paciente.edad}</span>` 
            : `<span class="campo-vacio">¡Necesitas agregar la fecha de nacimiento!</span>`;
        document.getElementById('detalle-genero').innerHTML = mostrarValor(paciente?.genero, 'No especificado');
        document.getElementById('detalle-estado').innerHTML = mostrarValor(paciente?.verificado, 'Estado no definido');
        document.getElementById('detalle-fecha-registro').innerHTML = mostrarValor(paciente?.fecha_creacion, 'Fecha de registro no disponible');
    }

    // Evento para abrir el modal de edición cuando se haga clic en el botón "Editar" dentro del modal de detalles
    if (btnEditarPaciente) {
        btnEditarPaciente.addEventListener('click', () => {
            abrirModalEdicion();
        });
    }

    // Función para abrir el modal de edición
    function abrirModalEdicion() {
        if (!pacienteSeleccionado) {
            alert('No se ha seleccionado ningún paciente.');
            return;
        }

        // Precargar los datos del paciente en el formulario
        document.getElementById('editar-rut').value = pacienteSeleccionado.rut || '';
        document.getElementById('editar-nombre').value = pacienteSeleccionado.nombres || '';
        document.getElementById('editar-apellido').value = pacienteSeleccionado.apellidos || '';
        document.getElementById('editar-email').value = pacienteSeleccionado.email || '';
        document.getElementById('editar-telefono').value = pacienteSeleccionado.telefono || '';
        document.getElementById('editar-direccion').value = pacienteSeleccionado.direccion || '';
        document.getElementById('editar-comuna').value = pacienteSeleccionado.comuna?.nombre || '';
        document.getElementById('editar-ciudad').value = pacienteSeleccionado.ciudad?.nombre || '';
        document.getElementById('editar-fecha-nacimiento').value = pacienteSeleccionado.fecha_nacimiento || '';
        document.getElementById('editar-genero').value = pacienteSeleccionado.genero || '';

        cerrarModal('modal-detalle-paciente');
        mostrarModal('modal-editar-paciente');
    }

    // Función para abrir el modal de confirmación de eliminación
    window.abrirModalEliminar = function() {
        if (!pacienteSeleccionado) {
            alert('No se ha seleccionado ningún paciente.');
            return;
        }
        mostrarModal('modal-eliminar-paciente');
    };

    // Evento para confirmar la eliminación
    document.getElementById('confirmar-eliminar').addEventListener('click', async () => {
        try {
            const response = await fetch(`/pacientes/${pacienteSeleccionado.id}`, {
                method: 'POST', // Se envía como POST y se añade un método _method DELETE para que Laravel lo acepte
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ _method: 'DELETE' }) // Laravel necesita que se envíe de esta forma para soportar DELETE
            });

            if (response.ok) {
                alert('Paciente eliminado con éxito.');
                cerrarModal('modal-eliminar-paciente');
                actualizarTabla(rowsPerPageSelect.value, currentPage); // Refrescar la tabla
            } else {
                const errorData = await response.json();
                alert('Error al eliminar el paciente: ' + errorData.message);
            }
        } catch (error) {
            console.error('Error al eliminar:', error);
            alert('Ocurrió un error al eliminar el paciente.');
        }
    });

    // Función para mostrar el modal
    function mostrarModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.visibility = 'visible';
            modal.classList.add('show');
        }
    }

    // Función para cerrar el modal
    function cerrarModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            modal.style.visibility = 'hidden';
        }
    }

    // Evento para cerrar el modal al hacer clic en el botón de cierre
    if (btnCloseModal) {
        btnCloseModal.addEventListener('click', () => cerrarModal('modal-detalle-paciente'));
    }

    // Evento para cerrar el modal con la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarModal('modal-detalle-paciente');
    });

    // Inicializar la tabla con los filtros actuales
    actualizarTabla(rowsPerPageSelect.value, 1);

    // Evento para guardar los cambios del paciente
    document.getElementById('form-editar-paciente').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch(`/pacientes/${pacienteSeleccionado.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(Object.fromEntries(formData)),
            });

            if (response.ok) {
                alert('Paciente actualizado con éxito.');
                cerrarModal('modal-editar-paciente');
                actualizarTabla(rowsPerPageSelect.value, currentPage); // Refrescar la tabla
            } else {
                const errorData = await response.json();
                alert('Error al actualizar el paciente: ' + errorData.message);
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            alert('Ocurrió un error al guardar los cambios.');
        }
    });
});
