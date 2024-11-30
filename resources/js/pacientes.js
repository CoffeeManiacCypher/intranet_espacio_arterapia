document.addEventListener('DOMContentLoaded', () => {
    const pacientesContainer = document.getElementById('pacientes-data');
    const rows = document.querySelectorAll('.tabla-dinamica tbody tr[data-has-modal="true"]');
    const btnCloseModal = document.querySelector('.btn-close-modal');

    if (!pacientesContainer) {
        console.error('No se encontró el contenedor de datos de pacientes.');
        return;
    }

    // Parsear los datos de pacientes desde el contenedor oculto
    const pacientesData = JSON.parse(pacientesContainer.textContent || '[]');

    // Añadir evento a cada fila para abrir el modal
    rows.forEach((row) => {
        row.addEventListener('click', () => {
            const pacienteId = row.getAttribute('data-paciente-id');
            abrirModalPaciente(pacienteId, pacientesData);
        });
    });

    // Función para abrir el modal y mostrar los datos del paciente
    function abrirModalPaciente(pacienteId, pacientesData) {
        const paciente = pacientesData.find((p) => p.id == pacienteId);

        if (paciente) {
            rellenarModal(paciente); // Rellena los datos del modal
            mostrarModal('modal-detalle-paciente'); // Muestra el modal
        } else {
            console.error('Paciente no encontrado:', pacienteId);
        }
    }

    // Función para rellenar los datos en el modal
    function rellenarModal(paciente) {
        document.getElementById('detalle-id').textContent = paciente.id || 'N/A';
        document.getElementById('detalle-nombre').textContent = paciente.nombres || 'N/A';
        document.getElementById('detalle-apellido').textContent = paciente.apellidos || 'N/A';
        document.getElementById('detalle-email').textContent = paciente.email || 'N/A';
        document.getElementById('detalle-telefono').textContent = paciente.telefono || 'N/A';
        document.getElementById('detalle-telefono-secundario').textContent = paciente.telefono_secundario || 'N/A';
        document.getElementById('detalle-direccion').textContent = paciente.direccion || 'N/A';
        document.getElementById('detalle-comuna').textContent = paciente.comuna?.nombre || 'N/A';
        document.getElementById('detalle-ciudad').textContent = paciente.ciudad?.nombre || 'N/A';
        document.getElementById('detalle-fecha-nacimiento').textContent = paciente.fecha_nacimiento || 'N/A';
        document.getElementById('detalle-edad').textContent = paciente.edad || 'N/A';
        document.getElementById('detalle-genero').textContent = paciente.genero || 'N/A';
        document.getElementById('detalle-estado').textContent = paciente.verificado || 'N/A';
        document.getElementById('detalle-fecha-registro').textContent = paciente.fecha_creacion || 'N/A';
    }

    // Función para mostrar el modal
    function mostrarModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.visibility = 'visible';
        modal.classList.add('show');
    }

    // Función para cerrar el modal
    function cerrarModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        modal.style.visibility = 'hidden';
    }

    // Evento para cerrar el modal desde el botón de cerrar
    btnCloseModal.addEventListener('click', () => {
        cerrarModal('modal-detalle-paciente');
    });

    // Evento para cerrar el modal al hacer clic fuera de él
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('modal-detalle-paciente');
        if (e.target === modal) {
            cerrarModal('modal-detalle-paciente');
        }
    });

    // Evento para cerrar el modal con la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModal('modal-detalle-paciente');
        }
    });
});
