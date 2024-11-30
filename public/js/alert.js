document.addEventListener("DOMContentLoaded", () => {
    /**
     * Asegurar que exista el contenedor de alertas
     */
    let alertContainer = document.getElementById("alert-container");
    if (!alertContainer) {
        alertContainer = document.createElement("div");
        alertContainer.id = "alert-container";
        alertContainer.style.position = "fixed";
        alertContainer.style.top = "10px";
        alertContainer.style.right = "10px";
        alertContainer.style.zIndex = "9999";
        alertContainer.style.display = "flex";
        alertContainer.style.flexDirection = "column";
        alertContainer.style.gap = "10px";
        document.body.appendChild(alertContainer);
    }

    /**
     * Crear y mostrar una alerta dinámica
     * @param {string} type - El tipo de alerta ('success', 'error', 'warning', 'info').
     * @param {string} message - El mensaje que se mostrará.
     * @param {number} duration - Tiempo en milisegundos antes de que la alerta desaparezca automáticamente.
     */
    window.showAlert = function (type, message, duration = 5000) {
        // Crear el contenedor de la alerta
        const alert = document.createElement("div");
        alert.classList.add("alert", `alert-${type}`);
        alert.innerHTML = `
            <span class="alert-text">${message}</span>
            <button class="alert-close" aria-label="Cerrar">
                <i class="bi bi-x"></i>
            </button>
            <div class="alert-progress">
                <span style="animation-duration: ${duration}ms;"></span>
            </div>
        `;

        // Agregar la alerta al contenedor
        alertContainer.appendChild(alert);

        // Cerrar la alerta manualmente
        const closeButton = alert.querySelector(".alert-close");
        closeButton.addEventListener("click", () => closeAlert(alert));

        // Cerrar la alerta automáticamente después de la duración
        setTimeout(() => {
            closeAlert(alert);
        }, duration);
    };

    /**
     * Cerrar una alerta específica con animación de salida
     * @param {HTMLElement} alert - El elemento de la alerta a cerrar.
     */
    function closeAlert(alert) {
        alert.classList.add("hide");
        alert.addEventListener(
            "animationend",
            () => {
                alert.remove(); // Eliminar el elemento del DOM
            },
            { once: true }
        );
    }

    /**
     * Escuchar eventos globales en botones para mostrar alertas
     * Utiliza data-attributes para especificar tipo y mensaje
     */
    document.addEventListener("click", (event) => {
        const button = event.target.closest("[data-alert]");
        if (button) {
            const type = button.dataset.alertType || "info"; // Tipo de alerta (default: info)
            const message = button.dataset.alertMessage || "Este es un mensaje genérico"; // Mensaje
            const duration = parseInt(button.dataset.alertDuration) || 5000; // Duración (default: 5000ms)

            showAlert(type, message, duration);
        }
    });

    /**
     * Mostrar ejemplo de integración con otros componentes
     */
    document.addEventListener("click", (event) => {
        const target = event.target;

        // Integración con modales (ejemplo)
        if (target.matches("#btn-confirm-modal")) {
            showAlert("success", "Operación confirmada desde el modal", 4000);
        }
    });
});
