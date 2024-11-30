document.addEventListener("DOMContentLoaded", () => {
    const radios = document.querySelectorAll(".radio");
    const resetButton = document.getElementById("reset-button");
    const customInputContainer = document.getElementById("custom-input-container");
    const customInput = document.getElementById("custom-input");

    // Efectos de sonido
    const selectSound = new Audio('/sounds/radio_select.wav');
    const unselectSound = new Audio('/sounds/radio_unselect.wav');

    // Evento para cada radio
    radios.forEach((radio) => {
        radio.addEventListener("click", () => {
            // Mostrar el botón al seleccionar un radio
            resetButton.classList.remove("hidden");

            // Reproducir sonido de selección
            if (radio.checked) {
                selectSound.currentTime = 0;
                selectSound.play();
            }

            // Mostrar input personalizado si se selecciona "Otro"
            if (radio.id === "radio-otro" && radio.checked) {
                customInputContainer.classList.remove("hidden");
                customInput.focus();
            } else {
                customInputContainer.classList.add("hidden");
            }
        });
    });

    // Evento para el botón de reset
    resetButton.addEventListener("click", () => {
        radios.forEach((radio) => {
            radio.checked = false; // Deseleccionar todos los radios
        });

        customInputContainer.classList.add("hidden"); // Ocultar el input personalizado
        resetButton.classList.add("hidden"); // Ocultar el botón de reset

        // Reproducir sonido de deselección
        unselectSound.currentTime = 0;
        unselectSound.play();
    });

    // Validación opcional al enviar un formulario (si aplica)
    document.addEventListener("submit", (e) => {
        const selected = Array.from(radios).some((radio) => radio.checked);

        if (!selected) {
            e.preventDefault();
            showAlert("warning", "Por favor, selecciona una opción antes de enviar", 4000);
        }
    });
});
