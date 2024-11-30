document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".checkbox");

    // Reproducir sonido al cambiar el estado
    const checkSound = new Audio('/sounds/checkbox_check.wav');
    const uncheckSound = new Audio('/sounds/checkbox_uncheck.wav');

    checkSound.volume = 0.5;
    uncheckSound.volume = 0.5;

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            const parent = checkbox.closest('.checkbox-wrapper');
            parent.classList.toggle('checked', checkbox.checked);

            // Reproducir sonidos según el estado
            if (checkbox.checked) {
                checkSound.currentTime = 0;
                checkSound.play();
            } else {
                uncheckSound.currentTime = 0;
                uncheckSound.play();
            }
        });
    });
});
